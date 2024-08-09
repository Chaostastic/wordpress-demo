<?php
namespace Demo\Pages;

class FormPage {
    public function loadHtml(): void {?>
        <form id="demo_form">
            <label>
                Name<br />
                <input type="text" name="name"><br />
            </label>
            <label>
                Email<br />
                <input type="email" name="email"><br />
            </label>
            <label>
                Telephone<br />
                <input type="tel" name="phone"><br />
            </label>
            <button type="submit">Submit</button>
        </form>
        <script>
            (function($) {
                $("#demo_form").submit(function(event){
                    event.preventDefault();
                    let form = $(this);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo get_rest_url(null, 'demo/v1/form');?>",
                        data: form.serialize()
                    })
                })
            })(jQuery);
        </script>
    <?php }
}