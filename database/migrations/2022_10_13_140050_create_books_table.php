<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->string('default_image');
            $table->string('slug');
            $table->integer('quantity');
            $table->foreignId('discount_id')->nullable()->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('sub_category_id')->constrained();
            $table->foreignId('book_detail_id')->constrained();
            // $table->unsignedBigInteger('sub_category_id');
            // $table->foreign('sub_category_id')->references('sub_category_id')->on('category_subcategory');
            // $table->unsignedBigInteger('category_id');
            // $table->foreign('category_id')->references('category_id')->on('category_subcategory');

            // $table->unsignedBigInteger('discount_id')->nullable();
            // $table->unsignedBigInteger('category_id');
            // $table->unsignedBigInteger('sub_category_id');
            // $table->unsignedBigInteger('book_detail_id');
            // $table->foreign('category_id')->references('id')->on('categories');
            // $table->foreign('sub_category_id')->references('id')->on('sub_categories');
            // $table->foreign('book_detail_id')->references('id')->on('book_details');

            $table->timestamps();
            // $table->foreign('discount_id')->references('id')->on('discounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
