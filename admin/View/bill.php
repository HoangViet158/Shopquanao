<?php require_once "sidebar.php"
?>
<div id="statistic-content" class="content-container">
    <div class="Mange_client">
        
    </div>
</div>
<script>
    const actionPermissions = {
        canView: <?php echo json_encode(hasAction(2, 'view')); ?>,
        canEdit: <?php echo json_encode(hasAction(2, 'edit')); ?>,
        canDelete: <?php echo json_encode(hasAction(2, 'delete')); ?>,
        canAdd: <?php echo json_encode(hasAction(2, 'add')); ?>
    };
</script>
<div id="bill-permissions"
     data-can-view="<?php echo json_encode(hasAction(2, 'view'));?>"
     data-can-edit="<?php echo json_encode(hasAction(2, 'edit')); ?>"
     data-can-delete="<?php echo json_encode(hasAction(2, 'delete')); ?>"
     data-can-add="<?php echo json_encode(hasAction(2, 'add')); ?>">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../public/admin/js/bill.js"></script>
