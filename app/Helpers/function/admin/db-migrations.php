<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('createTableCategory')) {
  function createTableCategory($action, $categoryTableName): void {

    if ($action == 'up') {
      Schema::create($categoryTableName, function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('parent_id')->nullable();
        $table->integer('deep')->default(0);
        $table->string("photo")->nullable();
        $table->string("photo_thumbnail")->nullable();
        $table->string("icon")->nullable();
        $table->string("font_icon")->nullable();
        $table->string("emoji_icon")->nullable();
        $table->integer("position")->default(0);
        $table->bigInteger("page_view")->default(0);
        $table->boolean("is_published")->default(false);
        $table->boolean("is_active")->default(true);
        $table->timestamps();
        $table->softDeletes();
      });


      Schema::create($categoryTableName . '_lang', function (Blueprint $table) use ($categoryTableName) {
        $table->bigIncrements('id');
        $table->bigInteger('category_id')->unsigned();
        $table->string('locale')->index();
        $table->string('slug')->nullable();
        $table->string('name')->nullable();
        $table->longText('des')->nullable();
        $table->longText('short_des')->nullable();
        $table->string('g_h1')->nullable();
        $table->string('g_title')->nullable();
        $table->text('g_des')->nullable();
        $table->unique(['category_id', 'locale']);
        $table->unique(['locale', 'slug']);
        $table->foreign('category_id')->references('id')->on($categoryTableName)->onDelete('cascade');
      });

    }

    if ($action == 'down') {
      Schema::dropIfExists($categoryTableName . '_lang');
      Schema::dropIfExists($categoryTableName);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('createTablePost')) {
  function createTablePost($action, $postTableName, $foreignKey): void {

    if ($action == 'up') {
      Schema::create($postTableName, function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('user_id')->nullable();
        $table->integer('category_id')->nullable();
        $table->string("photo")->nullable();
        $table->string("photo_thumbnail")->nullable();
        $table->string('youtube_code')->nullable();
        $table->json('available_colors')->nullable();
        $table->integer("position")->default(0);
        $table->bigInteger("page_view")->default(0);
        $table->boolean("is_published")->default(false);
        $table->boolean("is_active")->default(true);
        $table->string("gallery_view")->default('list');
        $table->date("published_at")->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
      });

      Schema::create($postTableName . "_lang", function (Blueprint $table) use ($postTableName, $foreignKey) {
        $table->bigIncrements('id');
        $table->bigInteger($foreignKey)->unsigned();
        $table->string('locale')->index();
        $table->string('slug')->nullable();
        $table->string('name')->nullable();
        $table->longText('des')->nullable();
        $table->text('short_des')->nullable();
        $table->string('g_h1')->nullable();
        $table->string('g_title')->nullable();
        $table->text('g_des')->nullable();
        $table->json('tags')->nullable();
        $table->string('youtube_title')->nullable();
        $table->unique([$foreignKey, 'locale']);
        $table->unique(['locale', 'slug']);
        $table->foreign($foreignKey)->references('id')->on($postTableName . "_lang")->onDelete('cascade');
      });

    }

    if ($action == 'down') {
      Schema::dropIfExists($postTableName . '_lang');
      Schema::dropIfExists($postTableName);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('createTablePostPhotos')) {
  function createTablePostPhotos($action, $photoTableName, $postTableName): void {
    if ($action == 'up') {
      Schema::create($photoTableName, function (Blueprint $table) use ($postTableName) {
        $table->bigIncrements('id');
        $table->bigInteger('post_id')->unsigned();
        $table->boolean("has_en")->default(false);
        $table->string("lang")->nullable();
        $table->string("photo")->nullable();
        $table->string("photo_thumbnail")->nullable();
        $table->boolean("is_active")->default(true);
        $table->integer("position")->default(0);
        $table->foreign('post_id')->references('id')->on($postTableName)->onDelete('cascade');
      });

      Schema::create($photoTableName . '_lang', function (Blueprint $table) use ($photoTableName) {
        $table->bigIncrements('id');
        $table->bigInteger('photo_id')->unsigned();
        $table->string('locale')->index();
        $table->string('name')->nullable();
        $table->longText('short_des')->nullable();
        $table->longText('des_up')->nullable();
        $table->longText('des_down')->nullable();
        $table->unique(['photo_id', 'locale']);
        $table->foreign('photo_id')->references('id')->on($photoTableName)->onDelete('cascade');
      });
    }

    if ($action == 'down') {
      Schema::dropIfExists($photoTableName . '_lang');
      Schema::dropIfExists($photoTableName);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('createTablePostPivot')) {
  function createTablePostPivot($action, $pivotTableName, $categoryTableName, $postTableName, $foreignKey): void {
    if ($action == 'up') {
      Schema::create($pivotTableName, function (Blueprint $table) use ($categoryTableName, $postTableName, $foreignKey) {
        $table->bigIncrements('id');
        $table->unsignedBiginteger('category_id');
        $table->unsignedBiginteger($foreignKey);
        $table->integer('position')->default(0);

        $table->foreign('category_id')->references('id')->on($categoryTableName)->onDelete('cascade');
        $table->foreign($foreignKey)->references('id')->on($postTableName)->onDelete('cascade');
      });
    }

    if ($action == 'down') {
      Schema::dropIfExists($pivotTableName);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('createTablePostTags')) {
  function createTablePostTags($action, $tagsTableName, $postTableName, $foreignKey): void {
    if ($action == 'up') {
      Schema::create($tagsTableName, function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->boolean("is_active")->default(true);
      });

      Schema::create($tagsTableName . '_lang', function (Blueprint $table) use ($tagsTableName) {
        $table->bigIncrements('id');
        $table->bigInteger('tag_id')->unsigned();
        $table->string('locale')->index();
        $table->string('slug')->nullable();
        $table->string('name')->nullable();
        $table->unique(['tag_id', 'locale']);
        $table->unique(['locale', 'slug']);
        $table->foreign('tag_id')->references('id')->on($tagsTableName)->onDelete('cascade');
      });

      Schema::create($tagsTableName . '_pivot', function (Blueprint $table) use ($postTableName, $tagsTableName, $foreignKey) {
        $table->bigIncrements('id');
        $table->unsignedBiginteger('tag_id');
        $table->unsignedBiginteger($foreignKey);

        $table->foreign('tag_id')->references('id')->on($tagsTableName)->onDelete('cascade');
        $table->foreign($foreignKey)->references('id')->on($postTableName)->onDelete('cascade');
      });
    }

    if ($action == 'down') {
      Schema::dropIfExists($tagsTableName . '_pivot');
      Schema::dropIfExists($tagsTableName . '_lang');
      Schema::dropIfExists($tagsTableName);
    }
  }
}

