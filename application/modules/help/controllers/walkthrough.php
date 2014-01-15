<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Walkthrough extends MX_Controller
{


    public function get_walkthrough($stage)
    {
        if ($this->uri->segment(2) === 'start')
        {
            $stage = 'start';
        }
        else
        {
            if ($stage !== 'home' && $stage !== 'profile' && $stage !== 'interest')
            {
                $stage = 'home';
            }
        }
        //echo $stage;
        $this->load->view('help/walkthrough', array(
                              'stage' => $stage
                          ));
    }
}

?>