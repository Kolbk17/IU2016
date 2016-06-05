<h1>List of Quadrants</h1>
<table>
    <tr>
        <th>Name</th>
        <th>ID</th>
        <th>Description</th>
    </tr>


    <?php foreach ($quadrants as $quadrant): ?>
    <tr>
        <td>
        <?php echo $quadrant['Quadrant']['name']; ?>
        </td>
        <td>
             <?php echo $quadrant['Quadrant']['id']; ?>
        </td>
        <td>
            <?php echo $quadrant['Quadrant']['description']; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php unset($post); ?>
</table>