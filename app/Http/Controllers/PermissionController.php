<?php

namespace App\Http\Controllers;

use App\Helpers\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function permissionCheck()
    {
        $permission = new Permissions;
        return $permission;
    }

    public function manageDepartment()
    {
        if($this->permissionCheck()->hasPermission('can_manage_department') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageAppSetting()
    {
        if($this->permissionCheck()->hasPermission('can_manage_app_setting') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageEmailSetting()
    {
        if($this->permissionCheck()->hasPermission('can_manage_email_setting') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageEmailTemplate()
    {
        if($this->permissionCheck()->hasPermission('can_manage_email_template') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageTicket()
    {
        if($this->permissionCheck()->hasPermission('can_manage_tickets') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageKB()
    {
        if($this->permissionCheck()->hasPermission('can_manage_kb') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageStaff()
    {
        if($this->permissionCheck()->hasPermission('can_manage_staff') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageUser()
    {
        if($this->permissionCheck()->hasPermission('can_manage_user') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageRole()
    {
        if($this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function seeDashboard()
    {
        if(Auth::user())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageLogoIcon()
    {
        if($this->permissionCheck()->hasPermission('can_manage_logo_icon') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageSocialLink()
    {
        if($this->permissionCheck()->hasPermission('can_manage_social_link') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageBanerText()
    {
        if($this->permissionCheck()->hasPermission('can_manage_baner_text') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageTestimonial()
    {
        if($this->permissionCheck()->hasPermission('can_manage_testimonial') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageService()
    {
        if($this->permissionCheck()->hasPermission('can_manage_service') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageAboutUs()
    {
        if($this->permissionCheck()->hasPermission('can_manage_aboutus') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageFooter()
    {
        if($this->permissionCheck()->hasPermission('can_manage_footer') || $this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    public function manageCounter()
    {
        if($this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageHowWork()
    {
        if($this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function manageInbox()
    {
        if($this->permissionCheck()->isAdmin())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}
