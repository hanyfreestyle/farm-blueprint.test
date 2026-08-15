<div class="image-preview-wrapper" style="position: relative; display: inline-block;">
  <img src="{{ Storage::disk('root_folder')->url($url) }}" class="preview-thumb" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" />

  <div class="preview-popup" style="
        display: none;
        position: absolute;
        top: 0;
        left: -400px;
        z-index: 999;
        border: 1px solid #ddd;
        background: #fff;
        padding: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        border-radius: 6px;

    ">
    <img src="{{ Storage::disk('root_folder')->url($url) }}" style="width: 400px; height: auto; border-radius: 4px;" />
  </div>
</div>
