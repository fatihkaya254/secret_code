<?php
require_once plugin_dir_path(__FILE__) . "../Controllers/Interrupt.php";
require_once plugin_dir_path(__FILE__) . "../Controllers/Limit.php";
if(!is_user_logged_in()){
  auth_redirect();
}
global $wpdb;
$lim = new Limit;
$user = wp_get_current_user();
$userId = $user->ID;
$ir = new Interrupt;
$history = $ir->get_history($userId);
$req = $lim->user_requests($userId);
$query = "SELECT *";
$query .= " FROM {$wpdb->prefix}sc_users";
$query .= " WHERE `user_id` = '$userId'";
$st = $wpdb->get_results($query, ARRAY_A);
$adress;
$phone;
foreach ($st as $key) {
  $adress = $key['adress'];
  $phone = $key['phone'];
}
?>
<div class="notice">
  <?php echo $lim->notice ?>
</div>


<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'mainpage')">Başlangıç</button>
  <button class="tablinks" onclick="openCity(event, 'settings')">Ayarlar</button>
  <button class="tablinks" onclick="openCity(event, 'history')">Geçmiş</button>
</div>

<div id="mainpage" class="tabcontent">
  <?php

  if ($req['date'] != '') {
    $message = 'Bekliyor';
    if($req['answer'] == 'undereview') $message = 'İnceleniyor';
    if($req['answer'] == 'accepted') $message = 'Kabul Edildi';
    if($req['answer'] == 'denied') $message = 'Reddedildi';
  ?>
    <h5>Açık Talebiniz Bulunmaktadır</h5>
    <p>Talep Tarihi: <?php echo $req['date'] ?></p>
    <p>Durum: <?php echo $message ?></p>
  <?php
  }
  ?>
  <div>
    <h5>Değer</h5>
    <p><?php echo $lim->user_score($userId) ?></p>
    <h5>Limit</h5>
    <p><?php echo $lim->limit ?></p>
    <?php

    if ((float)$lim->limit < (float)$lim->user_score($userId) && $req['date'] == '') {
    ?>
      <p>Limitin Üzerindesiniz</p>
      <form method="POST">
        <button name="interrupt" id="interrupt">Kesme Talebi Oluştur</button>
      </form>
      <p id="response"></p>
    <?php
    }
    ?>
  </div>

  <?php
  if (isset($_POST['interrupt'])) {
    $message = $lim->create_requests($userId);
  ?>
    <script>
      document.getElementById("response").innerText = "<?php echo $message ?>";
    </script>
  <?php
  }
  ?>

</div>

<div id="settings" class="tabcontent">
  <h5>Operator: <?php echo $user->user_login ?></h5>
  <h5>Telefon Numarası: <?php echo $phone ?></h5>
  <form method="POST">
    <div class="form">
      <input required maxlength="50" type="text" name="adress" id="adress" value="<?php echo $adress ?>">
      <button name="degistira" id="degistira">Değiştir</button>
    </div>
  </form>
  <form method="POST">
    <div class="form">
    <input required type="password" minlength="6" name="p1" placeholder="Yeni Şifre">
    <input required type="password" minlength="6" name="p2" placeholder="Yeni Şifreyi Tekrar">
    <button name="degistir" id="degistir">Değiştir</button>
    </div>
  </form>
</div>

<?php
if (isset($_POST['degistir'])) {
  $p1 = $_POST['p1'];
  $p2 = $_POST['p2'];
  if ($p1 != $p2) {
?>
    <script>
      alert('girilen şifreler eşleşmemektedir')
    </script>
<?php
  }
  else{
    wp_set_password( $p1, $userId);
    wp_logout();
  }
}
?>

<?php
if (isset($_POST['degistira'])) {
  $newAdress = $_POST['adress'];
  $wpdb->query("UPDATE {$wpdb->prefix}sc_users SET `adress` = '$newAdress' WHERE `user_id` = '$userId'");
  $st = $wpdb->get_results($query, ARRAY_A);
  $adress;
  $phone;
  foreach ($st as $key) {
    $adress = $key['adress'];
    $phone = $key['phone'];
    ?>
	<script>
		document.getElementById("adress").value = `<?php echo $adress ?>`;
	</script>
<?php
  }
}
?>

<div id="history" class="tabcontent">
  <table class="sc_table">
    <thead>
      <tr>
        <th>Tarih</th>
        <th>Deger</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($history as $key) {
        $date = $key['date'];
        $value = $key['value'];
      ?>

        <tr>
          <td><?php echo $date ?></td>
          <td><?php echo $value ?></td>
        </tr>

      <?php
      }
      ?>
    </tbody>
  </table>
</div>


<script>
  function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
  }
</script>
<style>
  table.sc_table {
    font-family: "Times New Roman", Times, serif;
    border: 1px solid #FFFFFF;
    width: 350px;
    height: 200px;
    text-align: center;
    border-collapse: collapse;
  }

  table.sc_table td,
  table.sc_table th {
    border: 1px solid #FFFFFF;
    padding: 3px 2px;
  }

  table.sc_table tbody td {
    font-size: 13px;
  }

  table.sc_table tr:nth-child(even) {
    background: #D0E4F5;
  }

  table.sc_table thead {
    background: #0B6FA4;
    border-bottom: 5px solid #FFFFFF;
  }

  table.sc_table thead th {
    font-size: 17px;
    font-weight: bold;
    color: #FFFFFF;
    text-align: center;
    border-left: 2px solid #FFFFFF;
  }

  table.sc_table thead th:first-child {
    border-left: none;
  }

  table.sc_table tfoot td {
    font-size: 14px;
  }

  .notice {
    flex-direction: column;
    border: 3px dotted black;
    min-height: 48px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: red;
    padding: 20px;
  }

  .tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #677dff;
  }

  /* Style the buttons inside the tab */
  .tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
  }

  /* Change background color of buttons on hover */
  .tab button:hover {
    background-color: #324def;
  }

  /* Create an active/current tablink class */
  .tab button.active {
    background-color: #324def;
  }

  /* Style the tab content */
  .tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
  }
  .form{
    display: flex;
    flex-direction: column;
  }
</style>