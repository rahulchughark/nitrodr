<?php include("includes/include.php"); ?>
<?php

        if (getSingleresult("select count(id) from sub_stage where stage_name='" . $stage . "' ")) { ?>
                <td>Sub Stage</td>
                <td><select id="add_comm" name="add_comm" onchange="payment_option(this.value)" class="form-control">
                        <option value="">--Select--</option>
                        <?php $sstage_sql = db_query("select * from sub_stage where stage_name='" . $stage . "'");
                        while ($sstage_data = db_fetch_array($sstage_sql)) {
                        ?>
                            <option <?= (($add_comm == $sstage_data['name']) ? 'selected' : '') ?> value="<?= $sstage_data['name'] ?>"><?= $sstage_data['name'] ?></option>
                        <?php } ?>
                    </select>
                </td>
        <?php } else {
                "Nosub";
          } ?>        
      