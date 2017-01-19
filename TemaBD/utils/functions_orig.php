<?php
  function redirect_to($path)
   {
     header("Location: " . $path);
	 exit;
   }

   function log_login($action)
	{
        $handle = fopen("logs/login_logout.txt", "a");
		 $time = date("h:i:sa d-m-Y");
         $string = "[{$time}] ({$_SESSION["username"]}) {$action}!\r\n";
         fwrite($handle,$string);
         fclose($handle);
	}
   
  function session_timeout()
  {
	  $timeout = 900;
	  if(isset($_SESSION["timeout"]))
	  {
		  $session_time = time() - $_SESSION["timeout"];
		  if($session_time > $timeout)
		  {
			  log_login(" Automatically logged out (15mins)!");
			  unset($_SESSION["admin_id"]);
			  unset($_SESSION["username"]);
			  unset($_SESSION["special_privileges"]);
			  unset($_SESSION["timeout"]);
			  $_SESSION["message"] = "<p class=\"bg-primary\">Session expired, please login again!</p>";
			  redirect_to("login.php");
		  }
	  }
  }

  
  //face redirectionarea catre noul site

   //verificam daca s-a apasat butonul de submit
   //pentru login.php
   function admin_verification()
   {
	   global $connection;
	   $message="";
      if(isset($_POST["submit"]))
      {
		 
		      $username = mysqli_real_escape_string($connection, $_POST["username"]);
			  $password = mysqli_real_escape_string($connection, md5($_POST["password"]));
			  $query = "SELECT * ";
	          $query.= "FROM admins ";
	          $query.= "WHERE password='{$password}' AND username='{$username}' LIMIT 1 ;";
	          $result = mysqli_query($connection,$query);
			  
			  
			  if($result)
	            {
		               $row=mysqli_fetch_assoc($result);
		            if($row)
		                {
						   		$_SESSION["admin_id"]=$row["id"];
                                $_SESSION["username"]=$username;
								$_SESSION["special_privileges"] = $row["special_privileges"];
								$_SESSION["timeout"] = time();
								log_login("LOG IN");
								mysqli_free_result($result);
			                    redirect_to("manage_pages.php");	  
		                }	 		 
	            } 
					$message = "<p class=\"bg-primary\">Username not found ";
			        $message .= "or password incorrect</p><br>";
      }
	  return $message;
	  
   }
   
   function find_page_by_name($page_name)
   {
	   global $connection;
	   $query = "SELECT * FROM pages ";
	   $query.= "WHERE page_name='{$page_name}' ";
	   $query.= "LIMIT 1;";
	   
	   $pages = mysqli_query($connection,$query);
	   if(!$pages)
	     {
		   die("Database error: " . mysqli_error($connection) );  
	     }
		else $page = mysqli_fetch_assoc($pages);
		mysqli_free_result($pages);
		return $page;
   }
   function find_all_pages()
   {
	   global $connection;
	   $query = "SELECT * FROM pages WHERE editable=1;";
	   
	   $pages = mysqli_query($connection,$query);
	   if(!$pages)
	     {
		   die("Database error: " . mysqli_error($connection) );  
	     }
		else return $pages;
   }

    function session_verification()
	{
		if(!isset($_SESSION["admin_id"]) || empty($_SESSION["admin_id"]))
		{
			redirect_to("login.php");
		}
	}
   function footer()
   {
      $string = "";   
       if(isset($_SESSION["admin_id"]))
            {
				if(isset($_GET["lang"]) && $_GET["lang"]=="en")
					$string.="<div id=\"admins\"><a href=\"manage_pages.php?lang=en\">Manage pages</a>    ";
				else $string.="<div id=\"admins\"><a href=\"manage_pages.php\">Manage pages</a>    ";
			  $string.="        <a href=\"logout.php\">Logout</a></div>";
            }
			else
			{
				$string.="<div id=\"admins\"><a href=\"login.php\">Login</a>";
			}
	  return $string;
   
   }
   
   //continut joburi
   function fetch_jobs_by_country($country,$camp)
   {
	   global $connection;
	   $string = "";
		  $query = "SELECT * FROM jobs WHERE {$camp}='{$country}';";
		  $jobs = mysqli_query($connection,$query);
		  if(!$jobs)
		  {
			  die("Query error(jobs): " . mysqli_error($connection));
		  }else
		  {
			  $string .= "<ol class=\"lista\">";
			  $i=0;
			  while($job = mysqli_fetch_assoc($jobs))
			  {
				  $id = urlencode($job["id"]);
				  $job_description = stripslashes($job["job_description"]);
				  $string .= "<li>";
				  $string .= "<div class=\"form-group\">";
				  $string .= "<label for=\"{$job["job_name"]}\">{$job["job_name"]}</label>&nbsp&nbsp&nbsp";
				  $string .= "<button class=\"btn btn-info btn-xs\" onclick=\"show_id('{$job["job_name"]}')\">Detalii</button>";
				  $string .= "<pre style=\"display: none;\" id=\"{$job["job_name"]}\">{$job_description}</pre>";
				  $string .= "</div></li>";
				  $i++;
			  }
			  $string .= "</ol>";
		  }
		 if($i==0) {
			 $string .= "<p class=\"bg-danger\">Nu exista oferte pentru acea tara</p>";
			 back_link("jobs.php");
		 }
		 mysqli_free_result($jobs);
		return $string;
   }
   function fetch_job_by_id($id)
   {
	   global $connection;
		  $query = "SELECT * FROM jobs;";
		  $jobs = mysqli_query($connection,$query);
		  if(!$jobs)
		  {
			  die("Query error(jobs): " . mysqli_error($connection));
		  }else
		  {
			  $job = mysqli_fetch_assoc($jobs);
			  mysqli_free_result($jobs);
			  return $job;
		  }
			  
   }
		  
    function arata_inscrisi()
	{
		global $connection;
		$string  = "<table class=\"table table-hover\">";
		$string .= "<thead><tr><th><b>#</b></th><th><b>Nume</b></th><th><b>Prenume</b></th><th><b>Mail</b></th>";
		$string .= "<th><b>Varsta</b></th><th><b>Tel</b></th><th><b>Data inscriere</b></th><th><b>Optiuni</b></th></tr></thead>";
		
		$query = "SELECT * FROM inscrisi ORDER BY time DESC;";
		$inscrisi = mysqli_query($connection,$query);
		if(!$inscrisi)
		{
			die("Querry error(inscrisi): " . mysqli_error($connection));
		}else
		{
			$i = 1;
			while($inscris = mysqli_fetch_assoc($inscrisi))
			{
				if($inscris["seen"]==1)
					$string .= "<tr class=\"succes\">";
				else $string .= "<tr>";
				$string .= "<td>{$i}</td>";
				$string .= "<td>{$inscris["nume"]}</td>";
				$string .= "<td>{$inscris["prenume"]}</td>";
				$string .= "<td>{$inscris["mail"]}</td>";
				$string .= "<td>{$inscris["varsta"]}</td>";
				$string .= "<td>{$inscris["tel"]}</td>";
				$string .= "<td>{$inscris["data_inscriere"]}</td>";
				$string .= "<td><a class=\"btn btn-primary btn-xs\" href=\"seen_inscris.php?id={$inscris["id"]}\">vazut</a><br><a class=\"btn btn-danger btn-xs\" href=\"delete_inscris.php?id={$inscris["id"]}\">delete</a></td>";
				$string .= "</tr>";
				$i++;
			}
		}
		
        $string .= "</table>";
		return $string;
	}
  
    function images($start,$stop)
	{
		for($i=$start;$i<=$stop;$i++)
			echo "<img  src=\"images/image_{$i}.png\" />";
	}
  
    function show_jobs()
	{
		global $connection;
		$string = "";
		$query = "SELECT * FROM jobs;";
		$jobs = mysqli_query($connection,$query);
		if(!$jobs)
		{
			die("Query error(edit jobs): " . mysqli_error());
		}
		else{
			$string  = "<table class=\"table table-hover\">";
			$string .= "<thead><tr><th><b>#</b></th><th><b>Nume job</b></th><th><b>Tara</b></th><th><b>Optiuni</b></th></thead>";
		
			$i=1;
			while($job = mysqli_fetch_assoc($jobs))
			{
             $string .= "<tr><td>{$i}</td><td>{$job["job_name"]}</td><td>{$job["tara"]}</td><td><a class=\"btn btn-primary btn-xs\" href=\"manage_jobs.php?id={$job["id"]}\">Edit</a><br><a class=\"btn btn-danger btn-xs\" href=\"delete_job.php?id={$job["id"]}\">Delete</a></td></tr>"; 
             $i++;			 
			}
			mysqli_free_result($jobs);
			$string .= "</table>";
			$string .= "<form action=\"manage_jobs.php\" method=\"post\">";
			$string .= "<input type=\"submit\" name=\"submit\" class=\"btn btn-primary\" value=\"Adauga job\"></form>";

		}
		return $string;
	}

    function edit_job()
    {
		global $connection;
	    $job_id= mysqli_real_escape_string($connection,$_GET["id"]);
		$query = "SELECT * FROM jobs WHERE id='{$job_id}' LIMIT 1;";
		$jobs = mysqli_query($connection,$query);
		$string="";
		if(!$jobs)
		{
			die("Query error(edit job): " . mysqli_error($connection));
		}
		else{
			$job = mysqli_fetch_assoc($jobs);
			$string .= "<form action=\"edit_job.php?id={$job["id"]}\" method=\"post\">";
			$string .= "<div class=\"form-group\">";
			$string .= "<label for=\"job_name\">Nume job</label>";
			$string .= "<input type=\"text\" class=\"form-control\" name=\"job_name\" id=\"job_name\" value=\"{$job["job_name"]}\" required>";
		    $string .= "</div>";
		    $string .= "<div class=\"form-group\">";
			$string .= "<label for=\"job_description\">Descriere job</label>";
			$string .= "<textarea rows=\"15\" class=\"form-control\" name=\"job_description\" id=\"job_description\" required>{$job["job_description"]}</textarea>";
		    $string .= "</div>";
			$string .= "<div class=\"form-group\">";
			$string .= "<label for=\"tara\">Tara</label>";
			$string .= "<input type=\"text\" class=\"form-control\" name=\"tara\" id=\"tara\" value=\"{$job["tara"]}\" required>";
		    $string .= "</div>";
			$string .= "<div class=\"form-group\">";
		    $string .= "<label for=\"Country\">Country</label>";
		    $string .= "<input type=\"text\" class=\"form-control\" name=\"country\" id=\"Country\" value=\"{$job["country"]}\" required>";
		    $string .= "</div>";
			$string .= "<input type=\"submit\" name=\"submit\" value=\"modifica\" class=\"btn btn-default\">";
			$string .= "</form>";
		}
		return $string;
	}	
    
	function add_job()
	{
		global $connection;
		$string = "";
		$string .= "<form action=\"add_job.php\" method=\"post\">";
		$string .= "<div class=\"form-group\">";
		$string .= "<label for=\"job_name\">Nume job</label>";
		$string .= "<input type=\"text\" class=\"form-control\" name=\"job_name\" id=\"job_name\" placeholder=\"Nume job\" required>";
		$string .= "</div>";
		$string .= "<div class=\"form-group\">";
		$string .= "<label for=\"job_description\">Descriere job</label>";
		$string .= "<textarea rows=\"15\" class=\"form-control\" name=\"job_description\" id=\"job_description\" placeholder=\"Descriere job\" required></textarea>";
		$string .= "</div>";
		$string .= "<div class=\"form-group\">";
		$string .= "<label for=\"tara\">Tara</label>";
		$string .= "<input type=\"text\" class=\"form-control\" name=\"tara\" id=\"tara\" placeholder=\"tara\" required>";
		$string .= "</div>";
	    $string .= "<div class=\"form-group\">";
		$string .= "<label for=\"Country\">Country</label>";
		$string .= "<input type=\"text\" class=\"form-control\" name=\"country\" id=\"Country\" placeholder=\"country\" required>";
		$string .= "</div>";
		$string .= "<div class=\"form-group\">";
		$string .= "<input type=\"submit\" name=\"submit\" value=\"adauga\" class=\"btn btn-success\">";
		$string .= "</div>";
		$string .= "</form>";
		return $string;
	}

	function show_admins()
	{
		global $connection;
		$string = "";
		$query = "SELECT * FROM admins;";
		$admins = mysqli_query($connection,$query);
		if(!$admins)
		{
			die("Query error(admin): " . mysqli_error());
		}
		else{
			$string  = "<table class=\"table table-hover\">";
			$string .= "<thead><tr><th><b>#</b></th><th><b>Username</b></th><th><b>Atributii speciale</b></th><th><b>Optiuni</b></th></thead>";
		    $i=1;
			while($admin = mysqli_fetch_assoc($admins))
			{
				$string .= "<tr><td>{$i}</td><td>{$admin["username"]}</td>";
				if($admin["special_privileges"]==1) $string .= "<td>DA</td>";
				   else $string .= "<td>NU</td>";
				$id=$admin["id"];
				$string .= "<td><a class=\"btn btn-danger btn-xs\" href=\"delete_admin.php?id={$id}\">delete</a></td>";
				$string .= "</tr>";
				$i++;
			}
           $string .= "</table>";
		}
		$string .= "<p class=\"bg-info\">Atributii speciale = poate edita/sterge/adauga administratori</p>";
	    $string .= "<form action=\"manage_admins.php\" method=\"post\"><input type=\"submit\" name=\"submit\" class=\"btn btn-success\" value=\"adauga\"></form>";
	   return $string;
	}
	
	function show_admin_form()
	{
		$string  ="";
		$string .= "<form name=\"admin_form\" onsubmit=\"return validity_admin_form()\" action=\"manage_admins.php\" method=\"post\">";
		$string .= "<div class=\"form-group\">";
		$string .= "<label for=\"username\">Username</label>";
		$string .= "<input type=\"text\" class=\"form-control\" name=\"username\" placeholder=\"username\" id=\"username\" >";
		$string .= "<p id=\"validate_username\" style=\"display: none;\" class=\"bg-danger\"></p>";
		$string .= "</div>";
		$string .= "<div class=\"form-group\">";
		$string .= "<label for=\"password\">Password</label>";
		$string .= "<input type=\"password\" class=\"form-control\" name=\"password\" placeholder=\"password\" id=\"password\" >";
		$string .= "<p id=\"validate_password\" style=\"display: none;\" class=\"bg-danger\"></p>";
		$string .= "</div>";
		$string .= "<div class=\"form-group\">";
		$string .= "<label for=\"privilegii\">Atributii speciale</label>";
		$string .= "<select multiple class=\"form-control\" name=\"special_privileges\" id=\"privilegii\">";
		$string .= "<option>DA</option>";
		$string .= "<option selected>NU</option>";
		$string .= "</select>";
		$string .= "</div>";
		$string .= "<div class=\"form-group\">";
		$string .= "<input type=\"submit\" name=\"submit_admin\" class=\"btn btn-primary\" value=\"submit\" >";
		$string .= "</div>";
		
		
		$string .= "</form>";
		return $string;
	}
     
	 function process_admin()
	 {
		global $connection;
		$username = mysqli_real_escape_string($connection,$_POST["username"]);
        $password = mysqli_real_escape_string($connection,$_POST["password"]);
        $password = md5($password);
		if($_POST["special_privileges"]=="DA") $special_privileges=1;
		   else $special_privileges=0;
		   
		   //verificare daca mai exista acest administratori
		   $query = "SELECT * FROM admins WHERE username='{$username}'";
		   $result = mysqli_query($connection,$query);
		   if($result && mysqli_fetch_assoc($result))
		   {
			   $_SESSION["message"]= "<p class=\"bg-danger\">Mai exista acest username</p>";
			   redirect_to("manage_admins.php");
		   }
		   //========================
		   
        $query = "INSERT INTO admins (username,password,special_privileges) ";
        $query .= "VALUES ('{$username}','{$password}',{$special_privileges});";
        $result = mysqli_query($connection,$query);
        if(!$result)
		{
			die("Query error(process admin()): " . mysqli_error($connection));
		}
        else{
			log_login("Added admin {$username} with special_privileges={$_POST["special_privileges"]}");
			redirect_to("manage_admins.php");
		}		
  
	 }
	
	function back_link($link,$nume="Inapoi")
	{
		echo "<a class=\"btn btn-warning\" href=\"{$link}\">$nume</a>";
	}
	
	function find_countries($camp)
	{
		global $connection;
		$countries = array();
		$query = "SELECT * FROM jobs;";
		$jobs = mysqli_query($connection,$query);
		if(!$jobs)
		{
			die("Query error(find countries): " . mysqli_error($connection));
		}
		else{
			while($job = mysqli_fetch_assoc($jobs))
			{
				if(!in_array($job[$camp],$countries)) $countries[] = $job[$camp];
			}
		 return $countries;
		}
	}
	
	function show_buttons_for_country($countries)
	{
		$string = "<div class=\"butoane_jobs\">";
		if(isset($_GET["lang"]) && $_GET["lang"]=="en")
		{
			$string .= "<h3 style=\"text-align:center;\">Available countries</h3>";
		}
		else $string .= "<h3 style=\"text-align:center;\">Tarile disponibile</h3>";
		 foreach($countries as $country)
		 {
			 $country2 = urlencode($country);
			 if(isset($_GET["lang"]) && $_GET["lang"]=="en")
		          {
			        $string .= "<a class=\"btn btn-primary btn-lg btn-block\" href=\"jobs.php?country={$country2}&lang=en\">{$country}</a>";
		          }
			 else $string .= "<a class=\"btn btn-primary btn-lg btn-block\" href=\"jobs.php?country={$country2}\">{$country}</a>"; //<img height=\"10px\" width=\"20px\" src=\"images/{$country}.jpg\" class=\"btn btn-default\">
		 }
		 $string .= "</div>";
	     return $string;
	}
	
	function navigation_bar()
	{
		if(isset($_GET["lang"]) && $_GET["lang"]=="en")
		  {
			  $home="Home";
			  $about = "About us";
			  $jobs ="Job offerings";
			  $documents = "Documents";
			  $contact ="Contact";
			  $sign= "Sign up";
			  $confid="Confidentiality";
			  $link1="index.php?lang=en";
			  $link2="about_us.php?lang=en";
			  $link3="jobs.php?lang=en";
			  $link4="acte.php?lang=en";
			  $link5="contact.php?lang=en";
			  $link6="fise_inscriere.php?lang=en";
			  $link7="confidentialitate.php?lang=en";
			  
		  }
		  else{
			  $home="Home";
			  $about = "Despre noi";
			  $jobs ="Oferte de munca";
			  $documents = "Acte necesare";
			  $contact ="Contact";
			  $sign= "Fise inscriere";
			  $confid="Confidentialitate";
			  $link1="index.php";
			  $link2="about_us.php";
			  $link3="jobs.php";
			  $link4="acte.php";
			  $link5="contact.php";
			  $link6="fise_inscriere.php";
			  $link7="confidentialitate.php";
		  }
		  $string ="";
		  $string .= "<ul class=\"navigation\">";
		  $string .="<li class=\"navigation\"><a href=\"{$link1}\"  class=\"navigation\">{$home}</a></li>";
		  $string .="<li class=\"navigation\"><a href=\"{$link2}\" class=\"navigation\">{$about}</a></li>";
		  $string .="<li class=\"navigation\"><a href=\"{$link3}\" class=\"navigation\">{$jobs}</a></li>";
		  $string .="<li class=\"navigation\"><a href=\"{$link4}\" class=\"navigation\">{$documents}</a></li>";
		  $string .="<li class=\"navigation\"><a href=\"{$link5}\" class=\"navigation\">{$contact}</a></li>";
		  $string .="<li class=\"navigation\"><a href=\"{$link6}\" class=\"navigation\">{$sign}</a></li>";
		  $string .="<li class=\"navigation\"><a href=\"{$link7}\" class=\"navigation\">{$confid}</a></li>";
		  $string .= "</ul>";
		  return $string;
	}
//pagina de fise de inscriere in romana se foloseste de urmatoarele 2 functii(prelucrare_fisa_inscris,show_fisa_inscris)
//cand este in engleza se afiseaza pagiona pentru inscrierea partenerilor	
	function prelucrare_fisa_inscris()
	   {
	   global $connection;
	    $message = "";
	     if(isset($_POST["submit"]))
		   {
			   $nume=$_POST["nume"];
			   $prenume=$_POST["prenume"];
			   $mail= $_POST["mail"];
			   $tel=$_POST["tel"];
			   $varsta = $_POST["varsta"];
			   //mail ===================== trimite o instiintare pe mail despre aplicare
			   
			   $to = "contact@angajarioriunde.ro";
               $subject = "O noua aplicare";
               $txt = "O persoana a completat fisa de inscriere de pe angajarioriunde.ro cu informatiile:\r\n";
               $txt .= "Nume: {$nume}\r\n";
               $txt .= "Prenume: {$prenume}\r\n";
               $txt .= "Mail: {$mail}\r\n";
               $txt .= "Varsta: {$varsta}\r\n";
               $txt .= "Tel: {$tel}\r\n";
               $header = "FROM: office@angajarioriunde.ro" . "\r\n";
               mail($to,$subject,$txt,$header); 
       
                //==========================
				$nume = mysqli_real_escape_string($connection,$nume);
				$prenume = mysqli_real_escape_string($connection,$prenume);
				$mail = mysqli_real_escape_string($connection,$mail);
				$rel = mysqli_real_escape_string($connection,$tel);
				$varsta = (int)$varsta;
				$time = time();
				$data_inscriere = mysqli_real_escape_string($connection,date("d/m/Y"));
                $query = "INSERT INTO inscrisi (nume,prenume,mail,varsta,tel,data_inscriere,time) ";
                $query .=	"VALUES ('{$nume}','{$prenume}','{$mail}',{$varsta},'{$tel}','{$data_inscriere}',{$time});";
                $result = mysqli_query($connection,$query);
                if(!$result)
				{
					$message = "<p class=\"bg-success\">Datele nu au fost trimise. Mai incercati!</p>";	
					die("Query error(fise): " . mysqli_error($connection));
				}
                else $message = "<p class=\"bg-success\">Datele au fost trimise.Va multumim!</p>";				
		   }
		   return $message;
	   }
	
	function show_fisa_inscris()
	{
	 $name = "Nume";
	 $prenume="Prenume";
	 $email="Adresa de email";
	 $age = "Varsta";
	 $tel = "Numar de telefon";
	 $buttn = "Trimite";
		 
	  $string ="";
	  $string .="<form name=\"fisa_inscriere\" onsubmit=\"return validity_tel()\" action=\"fise_inscriere.php\" method=\"post\">";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"nume\">{$name}</label>";
	  $string .="<input type=\"text\" class=\"form-control\" id=\"nume\" name=\"nume\" placeholder=\"Popescu\" required>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"prenume\">{$prenume}</label>";
	  $string .="<input type=\"text\" class=\"form-control\" id=\"prenume\" name=\"prenume\" placeholder=\"Ion\" required>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"email\">{$email}</label>";
	  $string .="<input type=\"email\" class=\"form-control\" id=\"email\" name=\"mail\" placeholder=\"example@domain.com\" required>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"varsta\">{$age}</label>";
	  $string .="<select id=\"varsta\" name=\"varsta\" multiple class=\"form-control\">";
        
       for($i=14;$i<=60;$i++)
           $string .= "<option>{$i}</option>";		   
	   
      $string .="</select>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .=" <label for=\"tel\">{$tel}</label>";
	  $string .="<input type=\"text\" class=\"form-control\" id=\"tel\" name=\"tel\" placeholder=\"07x xxx xxx\" required>";
	  $string .="<p class=\"bg-danger\" id=\"validate_tel\" style=\"display: none\"></p>";
	  $string .="</div>";
	  $string .="<button type=\"submit\" name=\"submit\" class=\"btn btn-default\">{$buttn}</button>";
	  $string .="</form>";
	  return $string;
	}
//functii pentru pagina cu parteneri
	
	function show_fisa_partener()
	{
		$name = "Last name";
		$prenume="First name";
		$email="Email";
		$coun = "Country";
		$website="Website address";
		$buttn = "Send";
		
	  $string ="";
	  $string .="<h3 style=\"text-align: center\">Become partener</h3>";
	  $string .="<form name=\"fisa_inscriere\" onsubmit=\"return validity_tel()\" action=\"fise_inscriere.php?lang=en\" method=\"post\">";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"nume\">{$name}</label>";
	  $string .="<input type=\"text\" class=\"form-control\" id=\"nume\" name=\"nume\" placeholder=\"Doe\" required>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"prenume\">{$prenume}</label>";
	  $string .="<input type=\"text\" class=\"form-control\" id=\"prenume\" name=\"prenume\" placeholder=\"John\" required>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"email\">{$email}</label>";
	  $string .="<input type=\"email\" class=\"form-control\" id=\"email\" name=\"mail\" placeholder=\"example@domain.com\" required>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .="<label for=\"country\">{$coun}</label>";
	  $string .="<input type=\"text\" class=\"form-control\" id=\"country\" name=\"country\" placeholder=\"United Kingdom\" required>";
	  $string .="</div>";
	  $string .="<div class=\"form-group\">";
	  $string .=" <label for=\"website\">{$website}</label>";
	  $string .="<input type=\"text\" class=\"form-control\" id=\"website\" name=\"website\" placeholder=\"ex:domain.com\" required>";
	  $string .="<p class=\"bg-danger\" id=\"validate_tel\" style=\"display: none\"></p>";
	  $string .="</div>";
	  $string .="<button type=\"submit\" name=\"submit\" class=\"btn btn-default\">{$buttn}</button>";
	  $string .="</form>";
	  return $string;
	}
	
	function prelucrare_fisa_partener()
	{
		global $connection;
	    $message = "";
	     if(isset($_POST["submit"]))
		   {
			   $nume=$_POST["nume"];
			   $prenume=$_POST["prenume"];
			   $mail= $_POST["mail"];
			   $country=$_POST["country"];
			   $website = $_POST["website"];
			   //mail ===================== trimite o instiintare pe mail despre aplicare
			   
			   $to = "contact@angajarioriunde.ro";
               $subject = "Un nou partener";
               $txt = "O persoana a completat fisa de inscriere partener de pe angajarioriunde.ro cu informatiile:\r\n";
               $txt .= "Nume: {$nume}\r\n";
               $txt .= "Prenume: {$prenume}\r\n";
               $txt .= "Mail: {$mail}\r\n";
               $txt .= "Tara: {$country}\r\n";
               $txt .= "Website: {$website}\r\n";
               $header = "FROM: office@angajarioriunde.ro" . "\r\n";
               mail($to,$subject,$txt,$header); 
       
                //==========================
				$nume = mysqli_real_escape_string($connection,$nume);
				$prenume = mysqli_real_escape_string($connection,$prenume);
				$mail = mysqli_real_escape_string($connection,$mail);
				$country = mysqli_real_escape_string($connection,$country);
				$website = mysqli_real_escape_string($connection,$website);
				$time = time();
				$data_inscriere = mysqli_real_escape_string($connection,date("d/m/Y"));
                $query = "INSERT INTO parteners (nume,prenume,mail,country,website,data_inscriere,time) ";
                $query .=	"VALUES ('{$nume}','{$prenume}','{$mail}','{$country}','{$website}','{$data_inscriere}',{$time});";
                $result = mysqli_query($connection,$query);
                if(!$result)
				{
					$message = "<p class=\"bg-success\">Datele nu au fost trimise. Mai incercati!</p>";	
					die("Query error(fise): " . mysqli_error($connection));
				}
                else $message = "<p class=\"bg-success\">Datele au fost trimise.Va multumim!</p>";				
		   }
		   return $message;
	}
	
	function arata_parteneri()
	{
		global $connection;
		$string  = "<table class=\"table table-hover\">";
		$string .= "<thead><tr><th><b>#</b></th><th><b>Nume</b></th><th><b>Prenume</b></th><th><b>Mail</b></th>";
		$string .= "<th><b>Tara</b></th><th><b>Website</b></th><th><b>Data inscriere</b></th><th><b>Optiuni</b></th></tr></thead>";
		
		$query = "SELECT * FROM parteners ORDER BY time DESC;";
		$parteners = mysqli_query($connection,$query);
		if(!$parteners)
		{
			die("Querry error(parteners): " . mysqli_error($connection));
		}else
		{
			$i = 1;
			while($partener = mysqli_fetch_assoc($parteners))
			{
				$string .= "<tr>";
				$string .= "<td>{$i}</td>";
				$string .= "<td>{$partener["nume"]}</td>";
				$string .= "<td>{$partener["prenume"]}</td>";
				$string .= "<td>{$partener["mail"]}</td>";
				$string .= "<td>{$partener["country"]}</td>";
				$string .= "<td>{$partener["website"]}</td>";
				$string .= "<td>{$partener["data_inscriere"]}</td>";
				if(isset($_GET["lang"]) && $_GET["lang"]=="en")
				   $string .= "<td><a class=\"btn btn-danger btn-xs\" href=\"delete_partener.php?id={$partener["id"]}&lang=en\">delete</a></td>";
				else $string .= "<td><a class=\"btn btn-danger btn-xs\" href=\"delete_partener.php?id={$partener["id"]}\">delete</a></td>";
				$string .= "</tr>";
				$i++;
			}
		}
		
        $string .= "</table>";
		return $string;
	}
	
	?>

