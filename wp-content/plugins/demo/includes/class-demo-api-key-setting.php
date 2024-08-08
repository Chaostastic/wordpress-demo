<?php
namespace Demo;

class APIKeySetting {
    public function init(): void {
        register_setting('general', 'demo_api_key');

        add_settings_field(
            'demo_api_key_field',
            'API Key',
            array($this, 'display'),
            'general',
        );
    }

    public function display(): void {
        $setting = get_option('demo_api_key');
        ?>
        <input type="text" name="demo_api_key" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
        <?php
    }
}