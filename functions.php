<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Setup My Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function agrion_child_theme_setup()
{
    load_child_theme_textdomain('agrion-child', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'agrion_child_theme_setup');

if (!function_exists('agrion_child_thm_parent_css')) :
    function agrion_child_thm_parent_css()
    {
        // loading parent styles
        wp_enqueue_style('agrion-parent-style', get_template_directory_uri() . '/style.css', array('agrion-fonts', 'agrion-icons', 'bootstrap', 'fontawesome'));

        // loading child style based on parent style
        wp_enqueue_style('agrion-style', get_stylesheet_directory_uri() . '/style.css', array('agrion-parent-style'));
    }

endif;
add_action('wp_enqueue_scripts', 'agrion_child_thm_parent_css');

// END ENQUEUE PARENT ACTION

function enqueue_investment_calculator_script() 
{
    wp_enqueue_script('investment-calculator', get_template_directory_uri() . '/assets/js/investment-calculator.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_investment_calculator_script');

function investment_calculator_shortcode() {
    ob_start();
    ?>
    <div id="investment-calculator">
    <h3>Investment ROI Calculator</h3>
	<div class="calculator-scenario-container">
		<div class= "scenario-container">
			<label><strong>Pick a Scenario:</strong></label>
            <select id="scenario">
                <option value="worst">Worst Case</option>
                <option value="medium">Average Case</option>
                <option value="best">Best Case</option>
            </select>
		</div>
		<div id="scenario-results" class="scenario-results-container">
			<p><strong>Mature Tree Yield:</strong> <span id="scenario-yield">--</span></p>
			<p><strong>Price per Fruit:</strong> <span id="scenario-price">--</span></p>
		</div>
	</div>
    <div class="calculator-container">
		
        <div class="form-container">
			<strong><label>Acreage:</label></strong>
            <input type="range" id="sizeAcre" min="1" max="50" step="1" value="1" oninput="this.nextElementSibling.value = this.value">
			<output>1</output> Acres <div id="trees" class="trees-container"><p>Trees Planted:<span class="trees-placeholder">--</span></p></div>
            
			<strong><label for="yearsSlider">Years:</label></strong>
            <input type="range" id="yearsSlider" name="yearsSlider" min="1" max="10" value="1">
			<span id="yearsLabel">1</span>
            
            <br>

            <button id="calculateBtn">Calculate</button>
        </div>
        <div id="results" class="results-container">
            <h4>Investment Summary</h4>
            <p><strong>Initial Investment:</strong> $ <span class="result-placeholder">--</span></p>
            <p><strong>Grand Total Revenue:</strong> $<span class="result-placeholder">--</span></p>
            <p><strong>Investor Share:</strong> $<span class="result-placeholder">--</span></p>
            <p><strong>Farmer Share:</strong> $<span class="result-placeholder">--</span></p>
            <p><strong>Company Share:</strong> $<span class="result-placeholder">--</span></p>
            <p><strong>Total Yield (Tonnes):</strong> <span class="result-placeholder">--</span></p>
            <p><strong>Trees Planted:</strong> <span class="result-placeholder">--</span></p>
			
        </div>
        
        </div>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('investment_calculator', 'investment_calculator_shortcode');
