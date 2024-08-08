<?php
namespace Demo;

class FormPage {
    public function loadHtml() {?>
        <form id="demo_form">
            <label>
                Name<br />
                <input type="text" name="name"><br />
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
            jQuery(document).ready(function($){
                $("#demo_form").submit(function(event){
                    alert('ok');
                    event.preventDefault();
                });
            });
        </script>
    <?php }
}