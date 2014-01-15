<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class custom_tab_test extends Web_Test_Case
{

    ///// All functions starting with "test" will be tested /////

    public function disabled_test_activate()
    {
        $user = $this->login();
        $page = $this->db_interface->add_object('pages', array('owner_id'=>$user['id']));
        $tab = $this->db_interface->add_object('custom_tabs', array('page_id'=>$page->page_id, 'activated' => false));
        $this->setMaximumRedirects(0);

        $this->logout();

        // try to do the requrest while logged out
        $data = $this->get('activate/1/1');
        $this->assertRedirect('');

        $this->login();

        //try to change someone else`s tab
        $data = $this->get('activate/1/1');
        $this->assertRedirect('');

        //try to change my tab
        $data = $this->get('activate/'.$tab->page_id.'/'.$tab->tab_id);
        $this->assertRedirect('interests//'.$tab->page_id.'/'.$tab->tab_id);

        $updated = $this->db_interface->db->get_where('custom_tabs', array('tab_id' => $tab->tab_id))->result_object();
        $this->assertEqual($updated[0]->activated, true);
    }

}