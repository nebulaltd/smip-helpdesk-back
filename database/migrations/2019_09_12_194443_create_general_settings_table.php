<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_name')->nullable();
            $table->text('app_description')->nullable();
            $table->text('app_keywords')->nullable();
            $table->string('app_language')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon_icon')->nullable();
            $table->string('header_title')->nullable();
            $table->text('header_subtitle')->nullable();
            $table->string('testimonial_title')->nullable();
            $table->text('testimonial_details')->nullable();
            $table->string('aboutus_keyword')->nullable();
            $table->string('aboutus_title')->nullable();
            $table->text('aboutus_details')->nullable();
            $table->string('social_title')->nullable();
            $table->string('gallery_title')->nullable();
            $table->text('gallery_details')->nullable();
            $table->string('service_title')->nullable();
            $table->text('service_details')->nullable();
            $table->string('how_work_title')->nullable();
            $table->text('how_work_details')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('contact_title')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('email_notification')->default(0);
            $table->string('email_sent_from')->nullable();
            $table->text('sms_api')->nullable();
            $table->boolean('sms_notification')->default(0);
            $table->boolean('registration_verification')->default(0);
            $table->boolean('email_verification')->default(0);
            $table->boolean('sms_verification')->default(0);
            $table->string('mail_driver')->nullable()->comment('Mail Driver');
            $table->string('smtp_host')->nullable()->comment('SMTP Host');
            $table->string('smtp_port')->nullable()->comment('SMTP Port');
            $table->string('smtp_username')->nullable()->comment('SMTP Username');
            $table->string('smtp_password')->nullable()->comment('SMTP Password');
            $table->string('smtp_encryption')->nullable()->comment('SMTP Encryption');
            $table->string('from_email')->nullable()->comment('From email');
            $table->string('from_name')->nullable()->comment('From name');
            $table->string('ticket_counter')->nullable()->comment('ticket_counter');
            $table->string('ticket_solved')->nullable()->comment('ticket_solved');
            $table->string('kb_counter')->nullable()->comment('kb_counter');
            $table->string('client_counter')->nullable()->comment('client_counter');
            $table->string('mailgun_domain')->nullable();
            $table->string('mailgun_api')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_settings');
    }
}
