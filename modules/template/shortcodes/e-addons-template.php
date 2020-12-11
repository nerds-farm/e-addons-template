<?php
namespace EAddonsTemplate\Modules\Template\Shortcodes;

use EAddonsForElementor\Base\Base_Shortcode;
use EAddonsForElementor\Modules\Template;

/**
 * Description of template
 *
 * @author fra
 */
class E_Addons_Template extends Base_Shortcode {
        
    public function get_name() {
        return 'e-addons-template';
    }
    
    public function get_pid() {
        return 1437;
    }
    
    public function get_icon() {
        return 'eadd-shortcode-template';
    }
    
    /**
     * Execute the Shortcode
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function do_shortcode($atts) {
        $atts = shortcode_atts(
                array(
                    'id' => '',
                    'post_id' => '',
                    'author_id' => '',
                    'user_id' => '',
                    'term_id' => '',
                    'ajax' => '',
                    'loading' => '',
                    'css' => false,
                ),
                $atts,
                $this->get_name()
        );

        $template_html = \EAddonsForElementor\Core\Managers\Template::e_template($atts['id'], $atts);                        
        return $template_html;
    }
    
}
