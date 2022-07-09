<?php
require_once plugin_dir_path(__FILE__) . "../Controllers/Interrupt.php";
$userId = get_current_user_id();
$ir = new Interrupt;
$history = $ir->get_history($userId);
?>
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
</style>