(() => {
    const saveIndicator = document.querySelector('[data-save-indicator]');

    if (!saveIndicator) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    const debounceTimers = new Map();

    const setIndicatorState = (state) => {
        saveIndicator.classList.remove('is-saving', 'is-error');

        if (state === 'saving') {
            saveIndicator.classList.add('is-saving');
            saveIndicator.textContent = 'جاري الحفظ...';
            return;
        }

        if (state === 'error') {
            saveIndicator.classList.add('is-error');
            saveIndicator.textContent = 'تعذر الحفظ';
            return;
        }

        saveIndicator.textContent = 'تم الحفظ';
    };

    const getQuestionBlocks = () => Array.from(document.querySelectorAll('[data-question-block]'));

    const readValue = (block) => {
        const type = block.dataset.questionType;

        if (type === 'yes_no' || type === 'single_choice') {
            return block.querySelector('[data-answer-input]:checked')?.value ?? '';
        }

        if (type === 'multi_choice') {
            return Array.from(block.querySelectorAll('[data-answer-input]:checked')).map((input) => input.value);
        }

        return block.querySelector('[data-answer-input]')?.value ?? '';
    };

    const readNotes = (block) => block.querySelector('[data-notes-input]')?.value ?? '';

    const collectAnswers = () => {
        const answers = {};

        getQuestionBlocks().forEach((block) => {
            answers[block.dataset.questionId] = readValue(block);
        });

        return answers;
    };

    const matchesDependency = (answerValue, operator, expectedValue) => {
        if (answerValue === undefined || answerValue === null || answerValue === '') {
            return false;
        }

        if (operator === 'contains') {
            if (Array.isArray(answerValue)) {
                return answerValue.includes(expectedValue);
            }

            return String(answerValue).includes(expectedValue);
        }

        if (Array.isArray(answerValue)) {
            return answerValue.length === 1 && String(answerValue[0]) === expectedValue;
        }

        return String(answerValue) === expectedValue;
    };

    const updateVisibility = () => {
        const answers = collectAnswers();

        document.querySelectorAll('[data-question-visibility]').forEach((wrapper) => {
            const dependsOnQuestionId = wrapper.dataset.dependsOnQuestionId;
            const operator = wrapper.dataset.dependencyOperator;
            const expectedValue = wrapper.dataset.dependencyValue ?? '';

            if (!dependsOnQuestionId || !operator) {
                wrapper.classList.remove('d-none');
                return;
            }

            const shouldShow = matchesDependency(answers[dependsOnQuestionId], operator, expectedValue);
            wrapper.classList.toggle('d-none', !shouldShow);
        });
    };

    const saveBlock = async (block) => {
        setIndicatorState('saving');

        try {
            const response = await fetch(block.dataset.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    value: readValue(block),
                    notes: readNotes(block),
                }),
            });

            if (!response.ok) {
                throw new Error('Save failed');
            }

            setIndicatorState('saved');
            updateVisibility();
        } catch (error) {
            setIndicatorState('error');
        }
    };

    const debounceSave = (block, delay = 500) => {
        const key = block.dataset.questionId;

        if (debounceTimers.has(key)) {
            window.clearTimeout(debounceTimers.get(key));
        }

        debounceTimers.set(key, window.setTimeout(() => {
            saveBlock(block);
            debounceTimers.delete(key);
        }, delay));
    };

    document.querySelectorAll('[data-notes-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const block = toggle.closest('[data-question-block]');
            const panel = block?.querySelector('[data-notes-panel]');

            if (!panel) {
                return;
            }

            panel.classList.add('is-open');
            panel.querySelector('[data-notes-input]')?.focus();
        });
    });

    document.querySelectorAll('[data-question-block]').forEach((block) => {
        const type = block.dataset.questionType;

        block.querySelectorAll('[data-answer-input]').forEach((input) => {
            input.addEventListener('change', () => {
                updateVisibility();

                if (['yes_no', 'single_choice', 'select', 'date', 'number'].includes(type)) {
                    saveBlock(block);
                    return;
                }

                if (type === 'multi_choice') {
                    debounceSave(block, 350);
                    return;
                }

                debounceSave(block, 550);
            });

            if (['text', 'textarea'].includes(type)) {
                input.addEventListener('blur', () => saveBlock(block));
                input.addEventListener('input', () => debounceSave(block, 700));
            }

            if (['number', 'date'].includes(type)) {
                input.addEventListener('blur', () => saveBlock(block));
            }
        });

        const notesInput = block.querySelector('[data-notes-input]');

        if (notesInput) {
            notesInput.addEventListener('blur', () => saveBlock(block));
            notesInput.addEventListener('input', () => debounceSave(block, 700));
        }
    });

    updateVisibility();
    setIndicatorState('saved');
})();
