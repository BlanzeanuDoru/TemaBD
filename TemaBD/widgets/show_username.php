<div class="dropdown login-dropdown">
    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION["username"]; ?></button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li><a>
            <form action="purchases.php?id=<?php echo $_SESSION["user_id"]; ?>" class="text-center" method="POST">
            	<input type="submit" class="btn btn-primary btn-xs logout" value="MyPurchases" name="purchases">
            </form>
    	</a></li>
    	<li><a>
            <form action="index.php" class="text-center" method="POST">
            	<input type="submit" class="btn btn-primary btn-xs logout" value="Logout" name="logout">
            </form>
    	</a></li>
    	
    </ul>	
</div>