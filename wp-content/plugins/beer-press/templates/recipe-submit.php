<!--
This template is used to display the recipe submit form. You can copy this file to your
theme folder and make changes if you wish.
-->

<form class="validate" action="<?php print $formaction; ?>" method="post" id="update" name="update">
    <?php $this->hidden_fields(); ?>

    <table class="<?php $this->class_name('table'); ?>">
        <tbody class="<?php $this->class_name('tbody'); ?>">
            <tr class="<?php $this->class_name('row', 'title'); ?>">
                <th valign="top" class="<?php $this->class_name('th', 'title'); ?>">
                    <?php $this->form_label('title'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td' ,'title'); ?>">
                    <?php $this->form_field('title'); ?>
                </td>
            </tr>
            <tr class="<?php $this->class_name('row', 'notes'); ?>">
                <th valign="top" class="<?php $this->class_name('th', 'notes'); ?>">
                    <?php $this->form_label('notes'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td' ,'notes'); ?>">
                    <?php $this->form_field('notes', 'textarea'); ?>
                </td>
            </tr>
            <tr class="<?php $this->class_name('row', 'category'); ?>">
                <th valign="top" class="<?php $this->class_name('th', 'category'); ?>">
                    <?php $this->form_label('category'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td' ,'category'); ?>">
                    <?php $this->form_field('category', 'select'); ?>
                </td>
            </tr>
            <tr class="<?php $this->class_name('row', 'servings'); ?>">
                <th valign="top" class="<?php $this->class_name('th', 'servings'); ?>">
                    <?php $this->form_label('servings'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td' ,'servings'); ?>">
                    <?php $this->form_field('servings'); ?>
                    <?php $this->form_field('servings_size', 'select'); ?>
                </td>
            </tr>
            <tr class="<?php $this->class_name('row', 'prep_time'); ?>">
                <th valign="top" class="<?php $this->class_name('th', 'prep_time'); ?>">
                    <?php $this->form_label('prep_time'); ?>
                </th>
                <td class="<?php $this->class_name('td' ,'prep_time'); ?>">
                    <?php $this->form_field('prep_time'); ?>
                </td>
                <th valign="top" class="<?php $this->class_name('th', 'cook_time'); ?>">
                    <?php $this->form_label('cook_time'); ?>
                </th>
                <td class="<?php $this->class_name('td' ,'cook_time'); ?>">
                    <?php $this->form_field('cook_time'); ?>
                </td>
            </tr>
            <tr class="<?php $this->class_name('row', 'ingredients'); ?>">
                <th valign="top" class="<?php $this->class_name('th', 'ingredients'); ?>">
                    <?php $this->form_label('ingredients'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td' ,'ingredients'); ?>">
                    <?php $this->form_field('ingredients', $this->options['form-type']); ?>
                </td>
            </tr>

            <tr class="<?php $this->class_name('row', 'instructions'); ?>">
                <th valign="top" class="<?php $this->class_name('th', 'instructions'); ?>">
                    <?php $this->form_label('instructions'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td' ,'instructions'); ?>">
                    <?php $this->form_field('instructions', 'textarea'); ?>
                </td>
            </tr>

            <?php if ($this->showCaptcha) : ?>
            <tr class="<?php $this->class_name('row', 'recaptcha'); ?>">
                <th valign="top" class="<?php $this->class_name('th' ,'recaptcha'); ?>">
                        <?php $this->form_label('recaptcha'); ?>
                </th>
                <td colspan="4" class="<?php $this->class_name('td' ,'recaptcha'); ?>">
                        <?php $this->recaptcha_field(); ?>
                </td>

            </tr>
            <?php endif; ?>

            <?php if (!$this->options['require-login'] and !is_user_logged_in()) : ?>
            <tr class="<?php $this->class_name('row', 'submitter'); ?>">
                <th valign="top" scope="row" class="<?php $this->class_name('th', 'submitter'); ?>">
                        <?php $this->form_label('submitter'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td', 'submitter'); ?>">
                        <?php $this->form_field('submitter'); ?>

                </td>
            </tr>
            <tr class="<?php $this->class_name('row', 'submitter_email'); ?>">
                <th valign="top" scope="row" class="<?php $this->class_name('th', 'submitter_email'); ?>">
                        <?php $this->form_label('submitter_email'); ?>
                </th>
                <td colspan="3" class="<?php $this->class_name('td', 'submitter_email'); ?>">
                        <?php $this->form_field('submitter_email'); ?>
                </td>
            </tr>
            <?php endif; ?>

        </tbody>
    </table>

    <?php $this->submit_button(); ?>
</form>
