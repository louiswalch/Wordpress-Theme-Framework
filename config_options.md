<table>
    <thead>
        <tr.>
            <Th.>Key</th>
            <th>Default</th>
            <th>Input Type</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="4"><b>Frontend Settings</b></td></tr>
        <tr><td>frontend/assets/version</td><td>false</td><td>bool</td><td>Add cachebusting version to assets paths</td></tr>
        <tr><td>frontend/assets/css</td><td>[]</td><td>array</td><td>Website CSS file(s). Paths relative to `asset/` directory.</td></tr>
        <tr><td>frontend/assets/css_print</td><td>[]</td><td>array</td><td>Print CSS file(s). Paths relative to `asset/` directory.</td></tr>
        <tr><td>frontend/assets/js</td><td>[]</td><td>array</td><td>Website JS file(s). Paths relative to `asset/` directory.</td></tr>
        <tr><td>frontend/query_params</td><td>[]</td><td>array</td><td>Add custom query params.</td></tr>
        <tr><td>frontend/admin_bar</td><td>true</td><td>bool</td><td>Hide the Wordpress Admin bar on Frontend</td></tr>
        <tr><td colspan="4"><b>Login Page Settings</b></td></tr>
        <tr><td>dashboard/login/css</td><td><i>empty</i></td><td>string</td><td>CSS file. Paths relative to `asset/` directory.</td></tr>
        <tr><td colspan="4"><b>Dashboard — General Settings</b></td></tr>
        <tr><td>dashboard/footer_credit</td><td>♥</td><td>string</td><td>Add custom CSS to dashboard.</td></tr>
        <tr><td>dashboard/css</td><td><i>empty</i></td><td>string</td><td>Add custom CSS to dashboard.</td></tr>
        <tr><td>support/tags</td><td>false</td><td>bool</td><td>Disable some Wordpress functionality.</td></tr>
        <tr><td>support/menus</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>support/widgets</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>support/customizer</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/delete_lock</td><td>[]</td><td>array</td><td>Block pages/posts from being deleted.</td></tr>
        <tr><td>dashboard/remove_roles</td><td>['subscriber', 'contributor']</td><td>array</td><td>Block pages/posts from being deleted.</td></tr>
        <tr><td>dashboard/admin_bar/howdy</td><td>false</td><td>bool</td><td>Display 'Howdy' from the top bar?</td></tr>
        <tr><td>dashboard/admin_bar/remove</td><td>['wp-logo','archive','updates','new-content']</td><td>array</td><td>Remove items from the top bar.</td></tr>
        <tr><td>dashboard/admin_bar/relocate</td><td>['options-general.php', 'tools.php', 'themes.php', 'plugins.php', 'edit.php?post_type=acf-field-group']</td><td>array</td><td>Relocate items from the sidebar into top bar.</td></tr>
        <tr><td colspan="4"><b>Dashboard — Metabox Settings</b></td></tr>
        <tr><td colspan="4">DOCUMENTAION COMING SOON</td></tr> 
        <tr><td colspan="4"><b>Dashboard — Content Editor Settings</b></td></tr>
        <tr><td>dashboard/editor/customize</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/css</td><td><i>empty</i></td><td>string</td><td></td></tr>
        <tr><td>dashboard/editor/height</td><td>200</td><td>int</td><td></td></tr>
        <tr><td>dashboard/editor/resize</td><td>true</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/media_buttons</td><td>false</td><td>bool</td><td></td></tr>
        <tr><td>dashboard/editor/buttons_1</td><td>['formatselect', 'bold', 'italic', 'bullist', 'numlist', 'blockquote',' alignleft', 'aligncenter','alignright', 'link', 'unlink', 'pastetext', 'removeformat']</td><td>array</td><td></td></tr>
        <tr><td>dashboard/editor/buttons_2</td><td>[]</td><td>array</td><td></td></tr>
        <tr><td>dashboard/editor/formats</td><td>Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre</td><td>string</td><td></td></tr>
        <tr><td colspan="4"><b>Image settings</b></td></tr>
        <tr><td>support/thumbnails</td><td>true</td><td>bool</td><td>Enable thumbnail support for this theme.</td></tr>
        <tr><td>image/sizes</td><td>['400', '600', '800', '1200', '1600', '2400']</td><td>array</td><td></td></tr>
        <tr><td>image/jpg_quality</td><td>90</td><td>int</td><td>JPG quality level for generated images.</td></tr>
        <tr><td>image/thumbnail/w</td><td>170</td><td>int</td><td>Generated thumbnail width.</td></tr>
        <tr><td>image/thumbnail/h</td><td>140</td><td>int</td><td>Generated thumbnail height.</td></tr>
        <tr><td>image/thumbnail/crop</td><td>true</td><td>bool</td><td>Generated thumbnail crop.</td></tr>
        <tr><td>image/remove_default</td><td>['medium', 'medium_large', 'large']</td><td>array</td><td>Remove specified default Wordpress image sizes.</td></tr>
        <tr><td>image/srcset_max</td><td>2400</td><td>int</td><td>Max width for responsive srcset images.</td></tr>
        <tr><td colspan="4"><b>Email Settings</b></td></tr>
        <tr><td>email/name</td><td>get_option('blogname')</td><td>string</td><td></td></tr>
        <tr><td>email/address</td><td>'noreply@' . $_SERVER['HTTP_HOST']</td><td>string</td><td></td></tr>
        <tr><td>email/mime</td><td>'text/html'</td><td>string</td><td></td></tr>
        <tr><td>email/send/change_password</td><td>false</td><td>bool</td><td>Send 'New User' notifications?</td></tr>
        <tr><td>email/send/new_user</td><td>false</td><td>bool</td><td>Send 'Notice of Password Change' email?</td></tr>
        <tr><td colspan="4"><b>Misc Settings</b></td></tr>
        <tr><td>comments</td><td>false</td><td>bool</td><td>Enable comment support for this theme.</td></tr>
        <tr><td>posts</td><td>true</td><td>bool</td><td>Enable posts for this theme.</td></tr>
    </tbody>
</table>