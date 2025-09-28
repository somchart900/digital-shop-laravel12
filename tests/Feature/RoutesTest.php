<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutesTest extends TestCase
{
  
    public function test_public_routes_are_accessible()
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('category'))->assertOk();

    }

  
    public function test_auth_guest_routes_are_accessible()
    {
        $this->get(route('auth.login'))->assertOk();
        $this->get(route('auth.register'))->assertOk();
        $this->get(route('auth.forget-password'))->assertOk();
    }

 
    public function test_admin_member_routes_redirect_if_not_logged_in()
    {
        $this->get(route('admin.dashboard'))->assertRedirect(); 
        $this->get(route('admin.user'))->assertRedirect();
        $this->get(route('admin.inbox'))->assertRedirect();
        $this->get(route('admin.setting.web'))->assertRedirect();
        $this->get(route('admin.setting.payment'))->assertRedirect();
        $this->get(route('admin.setting.api'))->assertRedirect();
        $this->get(route('admin.setting.other'))->assertRedirect();
        $this->get(route('admin.setting.category'))->assertRedirect();
        $this->get(route('admin.setting.product', ['category_id' => 1]))->assertRedirect();
        $this->get(route('admin.setting.item', ['category_id' => 1, 'product_id' => 1]))->assertRedirect();
        $this->get(route('admin.report'))->assertRedirect();
    }

    
    public function test_user_member_routes_redirect_if_not_logged_in()
    {
        $this->get(route('user.profile'))->assertRedirect();
        $this->get(route('user.order.list'))->assertRedirect();
        $this->get(route('user.topup'))->assertRedirect();
        $this->get(route('user.inbox'))->assertRedirect();
        $this->get(route('user.order.detail', ['id' => 1]))->assertRedirect();
    }
}

