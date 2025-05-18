<?php require_once "sidebar.php"
    
?>

<div id="statistic-content" class="content-container">
    <div class="Mange_client">
        <h3> Chi tiết phiếu nhập </h3>
    </div>
</div>
<div id="goodReceipt-permissions"
     data-can-view="<?php echo json_encode(hasAction(3, 'view'));?>"
     data-can-edit="<?php echo json_encode(hasAction(3, 'edit')); ?>"
     data-can-delete="<?php echo json_encode(hasAction(3, 'delete')); ?>"
     data-can-add="<?php echo json_encode(hasAction(3, 'add')); ?>">
</div>
<script>
    const actionPermissions = {
        canView: <?php echo json_encode(hasAction(3, 'view')); ?>,
        canEdit: <?php echo json_encode(hasAction(3, 'edit')); ?>,
        canDelete: <?php echo json_encode(hasAction(3, 'delete')); ?>,
        canAdd: <?php echo json_encode(hasAction(3, 'add')); ?>
    };
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../public/admin/js/goodsReceipt.js"></script>
