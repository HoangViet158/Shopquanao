<?php require_once "sidebar.php"
?>
<div id="statistic-content" class="content-container">
    <div class="Mange_client">
        <h3> Chi tiết đơn hàngaaaaaaaaa </h3>
    </div>
</div>
<script>
    const actionPermissions = {
        canView: <?php echo json_encode(hasAction(4, 'view')); ?>,
        canEdit: <?php echo json_encode(hasAction(4, 'edit')); ?>,
        canDelete: <?php echo json_encode(hasAction(4, 'delete')); ?>,
        canAdd: <?php echo json_encode(hasAction(4, 'add')); ?>
    };
</script>
<div id="promotion-permissions"
     data-can-view="<?php echo json_encode(hasAction(4, 'view'));?>"
     data-can-edit="<?php echo json_encode(hasAction(4, 'edit')); ?>"
     data-can-delete="<?php echo json_encode(hasAction(4, 'delete')); ?>"
     data-can-add="<?php echo json_encode(hasAction(4, 'add')); ?>">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../public/admin/js/promotion.js"></script>
