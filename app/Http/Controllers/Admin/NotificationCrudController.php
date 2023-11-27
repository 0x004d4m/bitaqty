<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NotificationRequest;
use App\Models\Client;
use App\Models\Notification;
use App\Models\PersonalAccessToken;
use App\Models\UserNotification;
use App\Models\Vendor;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(\App\Models\Notification::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/notification');
        $this->crud->setEntityNameStrings(__('admin.notification'), __('admin.notifications'));
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name'     => 'userable_type',
            'label'    => __('admin_fields.userable_type'),
            'type'     => 'closure',
            'function' => function ($entry) {
                return str_replace("App\\Models\\", "", $entry->userable_type);
            }
        ]);
        $this->crud->addColumn('userable_id', [
            'label' => __('admin_fields.user'),
            'type' => "select",
            'name' => 'userable_id',
            'entity' => 'userable',
            'attribute' => "name",
        ]);
        $this->crud->setColumnDetails('userable_id', [
            'label' => __('admin_fields.user'),
            'type' => "select",
            'name' => 'userable_id',
            'entity' => 'userable',
            'attribute' => "name",
        ]);
        $this->crud->column('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->column('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->column('image')->label(__('admin_fields.image'))->type('image');
        $this->crud->addButton('line', 'send-notification', 'view', __('admin.send'));
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(NotificationRequest::class);

        $this->crud->addField([   // select_from_array
            'name'        => 'userable_type',
            'label'       => __('admin_fields.userable_type'),
            'type'        => 'select_from_array',
            'options'     => ['App\Models\Client' => 'Client', 'App\Models\Vendor' => 'Vendor', null => null],
            'allows_null' => false,
        ]);
        $this->crud->addField([
            'label'                => __('admin_fields.user'),
            'type'                 => 'select2_from_ajax',
            'name'                 => 'userable',
            'entity'               => 'userable',
            'attribute'            => 'name',
            'data_source'          => url('admin/Users'),
            'placeholder'          => 'Select a User',
            'include_all_form_fields' => true,
            'minimum_input_length' => 0,
            'dependencies'         => ['userable_type'],
            'method'               => 'GET',
        ]);
        $this->crud->field('title')->label(__('admin_fields.title'))->type('text');
        $this->crud->field('description')->label(__('admin_fields.description'))->type('textarea');
        $this->crud->field('image')->label(__('admin_fields.image'))->type('image');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function send(Request $request, $id)
    {
        $Notification = Notification::where('id', $id)->first();
        if ($Notification) {
            if ($Notification->is_sent == 1) {
                return ["message" => __('admin.send_error_already_sent')];
            }
            if ($Notification->getTranslation('title', 'ar') && $Notification->getTranslation('title', 'en')) {
                if ($Notification->userable_type == 'App\Models\Client') {
                    if ($Notification->userable_id) {
                        $Client = Client::where('id', $Notification->userable_id)->first();
                        if($Client){
                            if($UserNotification = UserNotification::create([
                                "title" => $Notification->getTranslations('title'),
                                "description" => $Notification->getTranslations('description'),
                                "image" => $Notification->image,
                                "data" => $Notification->data,
                                "is_read" => $Notification->is_read,
                                "userable_type" => $Notification->userable_type,
                                "userable_id" => $Notification->userable_id,
                            ])){
                                $UserNotification->updateQuietly([
                                    "title" => $Notification->getTranslations('title'),
                                    "description" => $Notification->getTranslations('description'),
                                ]);
                                $ClientFcmTokens = PersonalAccessToken::where("name", 'ClientFcmToken')->where("tokenable_type", 'App\Models\Client')->where("tokenable_id", $Client->id)->get();
                                foreach($ClientFcmTokens as $ClientFcmToken){
                                    sendFCM($ClientFcmToken->token, $Notification->title, $Notification->description, $Notification->image);
                                }
                            }else{
                                return ["message" => __('admin.sent_error_message')];
                            }
                        }else{
                            return ["message" => __('admin.sent_error_message')];
                        }
                        $Notification->update(["is_sent"=>1]);
                        return 1;
                    } else {
                        $Clients = Client::get();
                        if ($Clients) {
                            foreach ($Clients as $Client) {
                                if ($UserNotification = UserNotification::create([
                                    "title" => $Notification->getTranslations('title'),
                                    "description" => $Notification->getTranslations('description'),
                                    "image" => $Notification->image,
                                    "data" => $Notification->data,
                                    "is_read" => $Notification->is_read,
                                    "userable_type" => $Notification->userable_type,
                                    "userable_id" => $Client->id,
                                ])) {
                                    $UserNotification->updateQuietly([
                                        "title" => $Notification->getTranslations('title'),
                                        "description" => $Notification->getTranslations('description'),
                                    ]);
                                    $ClientFcmTokens = PersonalAccessToken::where("name", 'ClientFcmToken')->where("tokenable_type", 'App\Models\Client')->where("tokenable_id", $Client->id)->get();
                                    foreach ($ClientFcmTokens as $ClientFcmToken) {
                                        sendFCM($ClientFcmToken->token, $Notification->title, $Notification->description, $Notification->image);
                                    }
                                }
                            }
                        } else {
                            return ["message" => __('admin.sent_error_message')];
                        }
                        $Notification->update(["is_sent" => 1]);
                        return 1;
                    }
                } elseif ($Notification->userable_type == 'App\Models\Vendor') {
                    if ($Notification->userable_id) {
                        $Vendor = Vendor::where('id', $Notification->userable_id)->first();
                        if ($Vendor) {
                            if ($UserNotification = UserNotification::create([
                                "title" => $Notification->getTranslations('title'),
                                "description" => $Notification->getTranslations('description'),
                                "image" => $Notification->image,
                                "data" => $Notification->data,
                                "is_read" => $Notification->is_read,
                                "userable_type" => $Notification->userable_type,
                                "userable_id" => $Notification->userable_id,
                            ])) {
                                $UserNotification->updateQuietly([
                                    "title" => $Notification->getTranslations('title'),
                                    "description" => $Notification->getTranslations('description'),
                                ]);
                                $VendorFcmTokens = PersonalAccessToken::where("name", 'VendorFcmToken')->where("tokenable_type", 'App\Models\Vendor')->where("tokenable_id", $Vendor->id)->get();
                                foreach ($VendorFcmTokens as $VendorFcmToken) {
                                    sendFCM($VendorFcmToken->token, $Notification->title, $Notification->description, $Notification->image);
                                }
                            } else {
                                return ["message" => __('admin.sent_error_message')];
                            }
                        } else {
                            return ["message" => __('admin.sent_error_message')];
                        }
                        $Notification->update(["is_sent" => 1]);
                        return 1;
                    } else {
                        $Vendors = Vendor::get();
                        if ($Vendors) {
                            foreach ($Vendors as $Vendor) {
                                if ($UserNotification = UserNotification::create([
                                    "title" => $Notification->getTranslations('title'),
                                    "description" => $Notification->getTranslations('description'),
                                    "image" => $Notification->image,
                                    "data" => $Notification->data,
                                    "is_read" => $Notification->is_read,
                                    "userable_type" => $Notification->userable_type,
                                    "userable_id" => $Vendor->id,
                                ])) {
                                    $UserNotification->updateQuietly([
                                        "title" => $Notification->getTranslations('title'),
                                        "description" => $Notification->getTranslations('description'),
                                    ]);
                                    $VendorFcmTokens = PersonalAccessToken::where("name", 'VendorFcmToken')->where("tokenable_type", 'App\Models\Vendor')->where("tokenable_id", $Vendor->id)->get();
                                    foreach ($VendorFcmTokens as $VendorFcmToken) {
                                        sendFCM($Vendor->fcm_token, $Notification->title, $Notification->description, $Notification->image);
                                    }
                                }
                            }
                        } else {
                            return ["message" => __('admin.sent_error_message')];
                        }
                        $Notification->update(["is_sent" => 1]);
                        return 1;
                    }
                } else {
                    $Clients = Client::get();
                    if ($Clients) {
                        foreach ($Clients as $Client) {
                            if ($UserNotification = UserNotification::create([
                                "title" => $Notification->getTranslations('title'),
                                "description" => $Notification->getTranslations('description'),
                                "image" => $Notification->image,
                                "data" => $Notification->data,
                                "is_read" => $Notification->is_read,
                                "userable_type" => 'App\Models\Client',
                                "userable_id" => $Client->id,
                            ])) {
                                $UserNotification->updateQuietly([
                                    "title" => $Notification->getTranslations('title'),
                                    "description" => $Notification->getTranslations('description'),
                                ]);
                                $ClientFcmTokens = PersonalAccessToken::where("name", 'ClientFcmToken')->where("tokenable_type", 'App\Models\Client')->where("tokenable_id", $Client->id)->get();
                                foreach ($ClientFcmTokens as $ClientFcmToken) {
                                    sendFCM($ClientFcmToken->token, $Notification->title, $Notification->description, $Notification->image);
                                }
                            }
                        }
                    }
                    $Vendors = Vendor::get();
                    if ($Vendors) {
                        foreach ($Vendors as $Vendor) {
                            if ($UserNotification = UserNotification::create([
                                "title" => $Notification->getTranslations('title'),
                                "description" => $Notification->getTranslations('description'),
                                "image" => $Notification->image,
                                "data" => $Notification->data,
                                "is_read" => $Notification->is_read,
                                "userable_type" => 'App\Models\Vendor',
                                "userable_id" => $Vendor->id,
                            ])) {
                                $UserNotification->updateQuietly([
                                    "title" => $Notification->getTranslations('title'),
                                    "description" => $Notification->getTranslations('description'),
                                ]);
                                $VendorFcmTokens = PersonalAccessToken::where("name", 'VendorFcmToken')->where("tokenable_type", 'App\Models\Vendor')->where("tokenable_id", $Vendor->id)->get();
                                foreach ($VendorFcmTokens as $VendorFcmToken) {
                                    sendFCM($Vendor->fcm_token, $Notification->title, $Notification->description, $Notification->image);
                                }
                            }
                        }
                    }
                    $Notification->update(["is_sent" => 1]);
                    return 1;
                }
            } else {
                return ["message" => __('admin.send_error_translation')];
            }
        } else {
            return ["message" => __('admin.send_error_notification_deleted')];
        }
    }
}
