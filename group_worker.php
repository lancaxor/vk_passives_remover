<?php
/**
 * Created by PhpStorm.
 * User: Alexey aka Reisshie
 * Date: 28.03.2017
 * Time: 0:28
 */

?>
<html>
<head>
    <title>Remove inactive users</title>
    <style>
        .content {
            width:50%;
            margin: 50px auto 0 auto;
        }
        .spaced {
            margin-top:7px;
        }
    </style>
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('input.remove_users').click(function() {

                var ajaxUrl = "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/ajax.php'?>";
                var groupId = $('input#group_id').val();

                $.ajax({
                    url: ajaxUrl,
                    data: {
                        is_ajax: true,
                        action:     'get_vk_group',
                        group_id:   groupId
                    }
                }).done(function(response) {
                    var data = JSON.parse(response);
                    if(data.error != undefined) {
                        alert(data.error);
                        return;
                    }
                    var confirmed = confirm('Are you sure you wanna remove deleted users from the group ' + data.group_name + '?');
                    if(confirmed) {
                        $('button#submit').click();
                    }
                });
            });
        });
    </script>
</head>
<body>
<div class="content">
    <div class="spaced description">
        The script will search for inactive users marked by <img src="https://vk.com/images/deactivated_50.png" width="30px" style="display:inline;vertical-align: middle">
        image and remove them from the group.
    </div><br/>
    <form method="post" id="group_id_form" action="#">
        <label for="group_id" class="spaced">Enter group ID:</label><br/>
        <input id="group_id" class="spaced" type="text" name="group_id" /><br/>
        <label for="test" class="spaced">Test mode (users won't be really removed) </label>
        <input type="checkbox" name="test" id="test" /><br/>
        <input type="button" class="remove_users spaced" value="Remove inactive users" />
        <button name="submit" id="submit" style="visibility: hidden">Submit</button>
        <input type="hidden" name="form-data" value="true" />
    </form>
</div>
</body>
</html>
<?php if(isset($_POST['form-data'])):
    if(!isset($_POST['group_id'])):
        die('ERROR: group_id WAS NOT SPECIFIED!');
    endif;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/VkGroupWorker.php';

    $groupId = $_POST['group_id'];
    $test = (isset($_POST['test']) && $_POST['test'] == 'on') ? 1 : 0;
    $vk = new VkGroupWorker();
    $vk->loadToken();
    $removedUsers = $vk->removeInactiveMembers($groupId, $test);
    echo '<H2>Removed users:</H2>';
    if(!empty($removedUsers) && is_array($removedUsers)):
        echo '<ol>';
        foreach($removedUsers as $user):
            echo <<<HTML
    <li><a href="http://vk.com/id{$user->uid}">{$user->uid}</a>: {$user->first_name} {$user->last_name} <img src="{$user->photo_50}" alt="User photo"/></li>
HTML;
        endforeach;
        echo '</ol>';
    else:
        echo '<H3>Nothing to remove</H3>';
    endif;


endif; ?>
