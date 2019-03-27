<?php

require_once('private/path_constants.php');
require_once(PRIVATE_PATH . '/functions.php');
require_once(PRIVATE_PATH . '/user_functions.php');
require_once(PRIVATE_PATH . '/authorisation_functions.php');
require_once(PRIVATE_PATH . '/vm_functions.php');
require_once(PRIVATE_PATH . '/customer_functions.php');
require_once(PRIVATE_PATH . '/environment_functions.php');

$page_title = 'System overview';

session_start();

session_expired();
is_logged_in();

include(SHARED_PATH . '/header.php');
//$welkom = 'Welkom, ' .$_SESSION["given_name"]. '. ';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
}


?>

<!-- Message aan gebruiker -->
<!--<div id="message-area" class='container'>-->
<!--    --><?php
//    // echo $welkom;
//    echo isset($message) ? $message : '';
//    unset($_SESSION['message']);
//    ?>
<!--</div>-->

<!-- Hier komt de content -->
<div id="content" class="container">

    <div class="system-overview-header-container">
        <h1>Systeem overzicht</h1>
        <div id="dropdown">
            <div class="dropdown-element">
                <form method="get">
                    <h2>Klant</h2>
                    <select name="customer_name" onchange="this.form.submit();">
                        <?php foreach (get_customerlist() as $customer) {

                            if (customer_has_environment($customer)) {

                                $customer_name = $customer->get_customer_name();
                                $selected = '';

                                if (isset($_GET['customer_name'])) {
                                    if ($_GET['customer_name'] == $customer_name) {
                                        $_SESSION['customer_name'] = $_GET['customer_name'];
                                        $environment_list = get_environmentlist();
                                        foreach ($environment_list as $environment) {
                                            if ($environment->get_customer_id() ==  get_customer_by_customer_name($customer_name)->get_customer_id()){
                                                $selected_environment = $environment;
                                                break;
                                            }
                                        }

                                        $selected = 'selected';
                                    }
                                } else if (isset($_SESSION['customer_name'])) {
                                    if ($_SESSION['customer_name'] == $customer_name) {
                                        $selected = 'selected';
                                    }
                                }


                                ?>


                                <option name="customer_name" value="<?= $customer_name ?>" <?= $selected ?>><?= $customer->get_customer_name() ?></option>


                            <?php }

                        }


                        ?>
                    </select>
                </form>
            </div>
            <div class="dropdown-element">
                <form method="get">
                    <label for="environment-dropdown">Omgeving</label>
                    <select id="environment-dropdown" name="environment_name" onchange="this.form.submit();">
                        <?php

                        if (isset($_GET['customer_name'])) {
                            $selected_customer = get_customer_by_customer_name($_GET['customer_name']);
                        } else if (isset($_SESSION['customer_name'])) {
                            $selected_customer = get_customer_by_customer_name($_SESSION['customer_name']);
                        } else {
                            foreach (get_customerlist() as $customer) {
                                if (customer_has_environment($customer)) {
                                    $selected_customer = $customer;
                                    ?><input type="hidden" name="environment_name" value="<?= get_environment_name_from_customer_id(get_customer_by_customer_name($customer_name)->get_customer_id()); ?>"/><?php

                                    break;
                                }
                            }

                        }



                        foreach (get_environmentlist() as $environment) {



                            $environment_name = $environment->get_environment_name();
                            $selected = '';

                            if ($environment->get_customer_id() == $selected_customer->get_customer_id()) {


                                if (isset($_GET['environment_name'])) {
                                    if ($_GET['environment_name'] == $environment_name) {
                                        $_SESSION['environment_name'] = $_GET['environment_name'];
                                        $selected = 'selected';
                                    }
                                } else if (isset($_SESSION['environment_name'])) {
                                    if ($_SESSION['environment_name'] == $environment_name) {
                                        $selected = 'selected';
                                    }
                                }
//                                else {
//                                    $environment_list = get_environmentlist();
//                                    $selected_environment = $environment_list[0];
//                                }


                                ?>

                                <option value="<?= $environment->get_environment_name() ?>" <?= $selected ?>> <?php echo $environment->get_environment_name() ?></option>

                                <?php
                            }
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>
    </div>


    <?php

    if (isset($_GET['environment_name'])) {
        $selected_environment = get_environment_by_environment_name($_GET['environment_name']);
    } else if (isset($_SESSION['environment_name'])){
        $selected_environment = get_environment_by_environment_name($_SESSION['environment_name']);
    }
//    else
//        {
//        $environment_list = get_environmentlist();
//        $selected_environment = $environment_list[0];
//    }

    ?>

    <div class="system-overview-servers-container">
        <div id="reload-content">
            <div class="progress-bar">
                <span class="progress-bar-fill" style="width: 0%"></span>
            </div>
            <div class="desc">
                <span>Ververst elke 10 seconden</span>
            </div>

            <?php foreach (get_sorted_virtualmachine_list_with_relations($selected_environment->get_environment_id()) as $vm) : ?>

                <?php
                if ($vm->getLatency() > 1.45) {
                    $image = "vm_red.png";
                } else if ($vm->getLatency() < 1.2) {
                    $image = "vm_green.png";
                } else {
                    $image = "vm_orange.png";
                }
                ?>

                <div id="server">
                    <div class="server-img">
                        <img src="<?php echo "img/" . $image ?>" alt="logo van virtuele machine">
                    </div>
                    <div class="server-info">
                        <div class="server-name">
                            <div><?php echo $vm->getName(); ?></div>
                        </div>
                        <div class="server-info-top">
                            <div class="key-value">
                                <div class="key">Latency:</div>
                                <div class="value"><?php echo $vm->getLatency(); ?> sec</div>
                            </div>
                            <div class="key-value">
                                <div class="key">Memory:</div>
                                <div class="value"><?php echo $vm->getMemory(); ?> GB</div>
                            </div>
                        </div>
                        <div class="server-info-bottom">
                            <div class="key-value">
                                <div class="key">Storage:</div>
                                <div class="value"><?php echo round($vm->getDiskSize(), 1); ?> GB</div>
                            </div>
                            <div class="key-value">
                                <div class="key">vCPU:</div>
                                <div class="value"><?php echo $vm->getVCPU(); ?></div>
                            </div>
                        </div>
                    </div>
                    <!--                    <div id="relations">-->
                    <!--                        --><?php //foreach ($vm->getRelationList() as $relation): ?>
                    <!--                            <ul>-->
                    <!--                                <li>--><?php //echo $relation->getVmNameFrom(); ?><!--</li>-->
                    <!--                                <li>--><?php //echo $relation->getVmNameTo(); ?><!--</li>-->
                    <!--                                <li>--><?php //echo $relation->getDescription(); ?><!--</li>-->
                    <!--                            </ul>-->
                    <!--                        --><?php //endforeach; ?>
                    <!--                    </div>-->
                    <div id="info-icon">
                        <a onClick="show_modal('<?= $vm->getName(); ?>')" class="close-modal"">
                        <i class="material-icons table-icons">info_outline</i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<?php foreach (get_sorted_virtualmachine_list_with_relations($selected_environment->get_environment_id()) as $vm) : ?>
    <div class="modal" id="modal-<?php echo $vm->getName(); ?>">
        <div id="modal-content">

            <div id="modal-title">
                <p>Servernaam:</p>
                <h1><?php echo $vm->getName(); ?></h1></div>
            <div id="IN-left">
                <i class="material-icons table-icons arrow">arrow_upward</i>
                <?php foreach ($vm->getOutgoingRelationList() as $relation): ?>
                    <div class="tooltip item">
                        <div><?php echo $relation->getVmNameFrom(); ?></div>
                        <span class="tooltiptext"><?php echo $relation->getDescription(); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="OUT-right">
                <i class="material-icons table-icons arrow">arrow_downward</i>
                <?php foreach ($vm->getIncomingRelationList() as $relation): ?>
                    <div class="tooltip item">
                        <div><?php echo $relation->getVmNameTo(); ?></div>
                        <span class="tooltiptext"><?php echo $relation->getDescription(); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="close-modal">
                <a onClick="hide_modal('<?= $vm->getName(); ?>')" class="close-modal"">
                <i class="material-icons table-icons">close</i>
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!--getIncomingRelationList()-->
<!--getOutgoingRelationList()-->


<script>

    function show_modal(server_name) {
        document.getElementById("modal-" + server_name).style.visibility = "visible";

    }

    function hide_modal(server_name) {
        document.getElementById("modal-" + server_name).style.visibility = "hidden";
    }

</script>

<!--Auto-refresh van het virtual machine overzicht -->
<script type="text/javascript" src="private/js/systemoverview.js"></script>

<?php include(SHARED_PATH . '/footer.php') ?> 