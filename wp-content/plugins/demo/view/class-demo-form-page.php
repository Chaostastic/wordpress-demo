<?php
namespace Demo;

class FormPage {
    public function loadHtml() {?>
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
            <button id="btn" type="submit">Submit</button>
        </form>
    <?php }
}