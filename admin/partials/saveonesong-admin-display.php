<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.frischware.net/wordpress
 * @since      1.0.0
 *
 * @package    Saveonesong
 * @subpackage Saveonesong/admin/partials
 */
?>
<div class='wrap'>
    <h1>SaveOneSong Plugin Settings</h1>
    <div id='app'>
        <h2>Notification Configuration</h2>
        <div class="box">
            <h3>Telegram</h3>
            <p>
                <label>
                    <span>Enabled?</span>
                    <input type="checkbox" v-model="vm.tg_enabled"   true-value="yes" false-value="no">
                </label>
            </p>
            <p>
                <label>
                    <span>Telegram Token:</span>
                    <input v-model='vm.tg_token' type='text' class='text-large' />
                </label>
            </p>
            <p>
                <label>
                    <span>Telegram Chat Id:</span>
                    <input v-model='vm.tg_chat_id' type='text' class='text-medium' />
                </label>
            </p>
            <p>
                <button
                    @click='saveOptions'
                    :disabled='isSaving'
                    id='sos-submit-settings' class='button button-primary'>Save</button>
                <img
                    v-if='isSaving == true'
                    id='loading-indicator' src='<?= get_site_url() ?>/wp-admin/images/wpspin_light-2x.gif' alt='Loading indicator' />
            </p>
            <p v-if='message'>{{ message }}</p>
        </div>
        
    </div>
</div>