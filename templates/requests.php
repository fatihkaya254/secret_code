<h1> Kesme Talepleri</h1>
<?php 
require_once plugin_dir_path(__FILE__) . "../Controllers/Interrupt.php";
require_once plugin_dir_path(__FILE__) . "../Controllers/Limit.php";
$ir = new Interrupt;
$lim = new Limit;
$list = $ir->list;
foreach ($list as $key) {
    $id = $key['id'];
    $userId = $key['user_id'];
    $date = $key['date'];
    $value = $key['value'];
    $answer = $key['answer'];
    $currentLimit = $key['current_limit'];
    $user_obj = get_user_by('id', $userId);
    $class = 'sc_form_row';
    if($answer == 'undereview') $class .= ' review'
    ?>
    <form class="<?php echo $class ?>" method="post" id="form<?php echo $id ?>">
        <div class="sc_form_info_row">
            <em> <?php echo $date ?> </em>
            <strong> <?php echo $user_obj->user_login ?> </strong>
            <span> Talep Anındaki Değer: <?php echo $value ?> </span>
            <span> Talep Anındaki Limit: <?php echo $currentLimit ?> </span>
            <span> Aktüel Değer: <?php echo $lim->user_score($userId) ?> </span>
            <span> Aktüel Limit: <?php echo $lim->limit ?> </span>
        </div>
        <div class="sc_form_submit_row">
            <input type="hidden" name="id" id="id<?php echo $id ?>" value="<?php echo $id ?>">
            <input type="hidden" name="user_id" id="user_id<?php echo $id ?>" value="<?php echo $userId ?>">
            <button class="button-accept wait"type="submit" name="ins" id="ins<?php echo $id ?>">İncele</button>
            <button class="button-accept reject"type="submit" name="dec" id="dec<?php echo $id ?>">Reddet</button>
            <button class="button-accept" type="submit" name="acp" id="acp<?php echo $id ?>">Kabul Et</button>
        </div>
    </form>
    <?php
}
    if (isset($_POST['ins'])){
        $ir-> send_response('undereview', $_POST['id'], 0);
        ?>
            <script>
                document.getElementById("form<?php echo $_POST['id'] ?>").style.backgroundColor = 'rgba(240, 240, 12, 0.2)';
            </script>
        <?php
    }
    if (isset($_POST['acp'])){
        $ir-> send_response('accepted', $_POST['id'], $_POST['user_id']);
        ?>
            <script>
                document.getElementById("form<?php echo $_POST['id'] ?>").style.backgroundColor = 'rgba(2, 118, 255, 0.6)';
                document.getElementById("form<?php echo $_POST['id'] ?>").style.display = 'none';
            </script>
        <?php
    } 
    if (isset($_POST['dec'])){
        $ir-> send_response('denied', $_POST['id'], 0);
        ?>
            <script>
                document.getElementById("form<?php echo $_POST['id'] ?>").style.backgroundColor = 'rgba(255, 59, 48, 0.6)';
                document.getElementById("form<?php echo $_POST['id'] ?>").style.display = 'none';
            </script>
        <?php
    }
?>

<style>
    .sc_form_row{
        background-color: white;
        margin: 12px 0;
        padding: 12px;
        height: 60px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .sc_form_info_row{
        height: 24px;
        display: flex;
        gap: 12px;
    }
    .sc_form_submit_row{
        height: 24px;
        display: flex;
        gap: 18px;
    }

    .button-accept {
      align-items: center;
      appearance: button;
      background-color: #0276FF;
      border-radius: 8px;
      border-style: none;
      box-shadow: rgba(255, 255, 255, 0.26) 0 1px 2px inset;
      box-sizing: border-box;
      color: #fff;
      cursor: pointer;
      display: flex;
      flex-direction: row;
      flex-shrink: 0;
      font-family: "RM Neue",sans-serif;
      font-size: 100%;
      line-height: 1.15;
      margin: 0;
      padding: 10px 21px;
      text-align: center;
      text-transform: none;
      transition: color .13s ease-in-out,background .13s ease-in-out,opacity .13s ease-in-out,box-shadow .13s ease-in-out;
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
    }

    .button-accept:active {
      background-color: #006AE8;
    }

    .button-accept:hover {
      background-color: #1C84FF;
    }
    .reject{
      background-color: rgba(0, 0, 0, 0);
      border: 1px solid rgb(255, 59, 48);
      color: rgb(255, 59, 48)
    }
    .reject:active {
      background-color: rgba(0, 0, 0, 0);
      text-decoration: underline;
    }
    .reject:hover {
      background-color: rgba(0, 0, 0, 0);
      text-decoration: underline;
    }
    .wait{
      background-color: rgba(0, 0, 0, 0);
      color: #0276FF
    }
    .wait:active {
      background-color: rgba(0, 0, 0, 0);
      text-decoration: underline;
    }

    .wait:hover {
      background-color: rgba(0, 0, 0, 0);
      text-decoration: underline;
    }
    .review {
      background-color: rgba(240, 240, 12, 0.2);
    }
</style>