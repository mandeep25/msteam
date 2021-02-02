<?php
include("include/config.php");
include("include/functions.php");

if (isset($_POST['submitzip']) && $_POST['submitzip'] == 'Submit') {
    $zip = $_POST['zip'];
    $zipstore = $obj->query("select z.storeid,z.state,z.city from tbl_store_zip z left join stores s on z.storeid=s.storeid where z.zip='$zip' and s.sectionid like '%1%' and z.zone_status=1 and z.store_status=1 and z.status=1 and s.status=1");
    $record = $obj->numRows($zipstore);
    if ($record > 0) {
        $ziprec = $obj->fetchNextObject($zipstore);
        setcookie('meal-kit-zone', '', time() - (86400 * 30 * 12), "/"); // 86400 = 1 day
		setcookie('postalcode', $zip, time() + (86400 * 30 * 12), "/"); // 86400 = 1 dayy
        zip_redirect($ziprec->city, $ziprec->state);
    } else {
        setcookie('meal-kit-zone', '1', time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
		setcookie('postalcode', $zip, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
		header("Location:" . SITE_URL . "indian-meal-kit-delivery");
        exit;
    }
}

//Dyamic Locaton based URL
if ($_COOKIE['url'] != '') {
    $urlarr = explode(',', $_COOKIE['url']);
    $url = $urlarr[0] . '-' . $urlarr[1];
} else {
    $url = '';
}
?>
<!DOCTYPE html>
<html lang="en-us">
    <head>
        <?php include("metas.php"); ?>
        <?php include("css.php"); ?>
        <script type="text/javascript">
        function zipSubmit(){
        var zipVal = document.getElementById("zipcode").value;
        if(zipVal!='' && zipVal.length=='5'){
       $("#zipsubmitbtn").click();
       }else{
       document.getElementById("zipcode").focus();
       var input = document.getElementById("zipcode")
         input.style.border = "2px solid red";  
       } }
        </script>
        <?php include("common-head.php"); ?>
        <script type="application/ld+json">
            { "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Quicklly",
            "legalName" : "Quicklly",
            "url": "https://www.quicklly.com/",
            "logo": "https://www.quicklly.com/images/quicly-logo-black.png",
            "foundingDate": "2020",
            "founders": [
            {
            "@type": "Person",
            "name": "Quicklly"
            } ],
            "address": {
            "@type": "PostalAddress",
            "addressLocality": "Chicago",
            "addressRegion": "Illinois",
            "postalCode": "60610",
            "addressCountry": "USA"
            },
            "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer support",
            "telephone": "[+1(224)366-0987]",
            "email": "hello@quicklly.com"
            },
            "sameAs": [ 
            "https://www.facebook.com/quickllyfoodandgroceries/",
            "https://twitter.com/Quicklly_",
            "https://in.linkedin.com/company/myvalue365-e-commerce-pvt-ltd-",
            "https://www.youtube.com/channel/UCNHYZ9SGLVejqPwHG8j6EKw/",
            "https://www.instagram.com/quickllyfoodandgroceries/",
            "https://www.pinterest.com/Quicklly2020/"
            ]}
        </script>

    </head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PG9CTB3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php include("headerouter.php"); ?>

    <div class="clsBanner">
        <div class="clsSlider">
		<?php $sectionarr=$obj->query("select id,section_name,section_photo,section_url,section_slug from tbl_section where status=1 order by display_order");
		while($resultsection=$obj->fetchNextObject($sectionarr)){ 
		$sectionid='';
		if($resultsection->id=='4'){ $sectionid=''; }
		elseif($resultsection->id=='5'){ $sectionid='#'.$resultsection->section_slug; }
        elseif($resultsection->id=='6'){ $sectionid=''; } ?>
        
        <?php if (($resultsection->id == 4) || ($resultsection->id == 6)) { ?>
            <a class="inactive-link" href="<?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ echo "indian-meal-kit-delivery"; }else{ echo str_replace('-delivery', '', $resultsection->section_url); if($url!=''){ echo '/near-me-in-'.$url; } ?><?php echo $sectionid; } ?>" title="<?php echo $resultsection->section_name; ?>">
                <img src="images/banner/<?php echo $resultsection->section_photo; ?>" alt="<?php echo $resultsection->section_name; ?>" />
                <span class="clsTitle"><?php echo ucwords($resultsection->section_name); ?></span>
                <span class="clsExplore">Order Now</span>
            </a>
        <?php } else { ?>
            <a class="inactive-link" href="<?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ echo "indian-meal-kit-delivery"; }else{ echo $resultsection->section_url; if($url!=''){ echo '/near-me-in-'.$url; } ?><?php echo $sectionid; } ?>" title="<?php echo $resultsection->section_name; ?>">
                <img src="images/banner/<?php echo $resultsection->section_photo; ?>" alt="<?php echo $resultsection->section_name; ?>" />
                <span class="clsTitle"><?php echo ucwords($resultsection->section_name); ?></span>
                <span class="clsExplore">Order Now</span>
            </a>
            <?php } ?>
           
			<?php } ?>
        </div>
<?php //if(!isset($_COOKIE['postalcode']) || $_COOKIE['postalcode']==''){ ?>
        <div class="clsPincode">
		<form name="zipsearch" id="zipForm" action="" method="post" enctype="multipart/form-data">
            <input type="text" name="zip" id="zipcode" placeholder="Enter Zip Code" maxlength="5" 
            minlength="5" onKeyPress="return isNumberKey(event,this)" required value="<?php echo $_COOKIE['postalcode']; ?>" autofocus />
            <input type="submit" id="zipsubmitbtn" name="submitzip"  value="Submit" onClick="return checkStoreZip();"/>
			</form>
        </div><?php //} ?>
    </div>
<?php $storearr=$obj->query("select storename,photo from stores where status=1 and in_stock=1 order by display_order limit 1, 9");
if($obj->numRows($storearr)>0){ ?>
    <div class="clsTopStores">
        <h2 class="headBld">Free Same - Day Delivery From Top Rated Stores</h2>

        <div class="clsSlider">
		<?php while($resultstore=$obj->fetchNextObject($storearr)){ ?>
            <a class="inactive-link" href="javascript:void()">
                <span style="background-image: url(seller/upload_images/store/thumb/<?php echo $resultstore->photo; ?>);"></span>
                <!-- <img src="seller/upload_images/store/thumb/<?php echo $resultstore->photo; ?>" alt="<?php echo $resultstore->storename; ?>" /> -->
            </a>
			<?php } ?>
        </div>
    </div>
<?php } //numrows ?>

    <div class="clsCategories clsPgWidth">
        <!--<h2>Groceries</h2>-->

        <div class="clsGrd">
            <a  <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{ if($_COOKIE['postalcode']!=''){ ?> href="indian-grocery<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>/order-fresh-produce" <?php }else{ ?> href="javascript:void(0)" onClick="zipSubmit()" <?php } } ?>>
                <div>
                    <img src="images/category/freshproduce.png" alt="Fresh Produce" />
                </div>
                <span>Fresh Produce</span>
            </a>
            <a   <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{  if($_COOKIE['postalcode']!=''){ ?>href="indian-grocery<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>/order-groceries" <?php }else{ ?> href="javascript:void(0)" onClick="zipSubmit()" <?php } } ?>>
                <div>
                    <img src="images/category/grocery.png" alt="Groceries" />
                </div>
                <span>Groceries</span>
            </a>
            <a   <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{  if($_COOKIE['postalcode']!=''){ ?>href="indian-grocery<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>/order-halal-meat-poultry"<?php }else{ ?> href="javascript:void(0)" onClick="zipSubmit()" <?php } } ?>>
                <div>
                    <img src="images/category/meat.png" alt="Meat Products" />
                </div>
                <span>Meat and Poultry</span>
            </a>
        </div>
    </div>

    <div class="clsCategories clsPgWidth">
       <!-- <h2>Food</h2>-->


        <div class="clsGrd">
            <a   <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{ if($_COOKIE['postalcode']!=''){ ?>href="indian-catering<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>" <?php }else{ ?> href="javascript:void(0)" onClick="zipSubmit()" <?php } } ?>>
                <div>
                    <img src="images/category/catering.png" alt="Catering" />
                </div>
                <span>Catering</span>
            </a>
            <a  <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{ if($_COOKIE['postalcode']!=''){ ?>href="indian-food-delivery<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>"<?php }else{ ?> href="javascript:void(0)" onClick="zipSubmit()" <?php } } ?>>
                <div>
                    <img src="images/category/food.png" alt="foods" />
                </div>
                <span>Food</span>
            </a>
            <a href="indian-meal-kit-delivery">
                <div>
                    <img src="images/category/mealplan.png" alt="Meal Kit" />
                </div>
                <span>Meal Kit</span>
            </a>
        </div>
    </div>

    <div class="clsFeatures">

         <div class="clsLeft" style="background-image:url(images/feature/app-Mobile-banner.jpg);">
            <div class="clsPgWidth">
                <div class="clsContent">

                    <h3>Download Quicklly iOS App!</h3>

                    <p>Enjoy $10 OFF $50 on your 1st order. Use coupon code -  QUICKLLYAPP</p>

                    <a class="inactive-link" href="https://apps.apple.com/app/id1536958907" target="_blank">Download Now</a>

                </div>
            </div>
        </div>
        
        <div class="clsRight" style="background-image:url(images/feature/grocery.png);">
            <div class="clsPgWidth">
                <div class="clsContent">
                    <h3>Explore Authentic Indian Groceries</h3>
                    <p>Indian Groceries delivered to the comfort of your doorstep. We are here to make your life easier!</p>
                    <a class="inactive-link" <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{ ?> href="/indian-grocery-delivery<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>" <?php } ?>>Order Indian Groceries Now</a>
                </div>
            </div>
        </div>

        <div class="clsLeft" style="background-image:url(images/feature/food.png);">
            <div class="clsPgWidth">
                <div class="clsContent">
                    <h3>Satisfy Your Indian Food Cravings</h3>
                    <p>Quicklly offers a wide range of Authentic Indian food dishes from local Indian restaurants delivered to your doorstep.</p>
                    <a class="inactive-link" <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{ ?> href="/indian-food-delivery<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>" <?php } ?>>Order Indian Food Online</a>
                </div>
            </div>
        </div>

        <div class="clsRight" style="background-image:url(images/feature/tiffin.png);">
            <div class="clsPgWidth">
                <div class="clsContent">
                    <h3>Comfort of Warm Indian Meals</h3>
                    <p>Quicklly offers subscription to a wide range of cooked Indian meal tiffin services, delivered to your doorstep as per your convenience.</p>
                    <a class="inactive-link" <?php if(isset($_COOKIE['meal-kit-zone']) && $_COOKIE['meal-kit-zone']==1){ ?> href="indian-meal-kit-delivery" <?php }else{ ?> href="/indian-tiffin<?php if($url!=''){ echo '/near-me-in-'.$url; } ?>" <?php } ?>>Ghar Ka Khana Now</a>
                </div>
            </div>
        </div>
    </div>
	<!-- explore now modal starts-->
<!--<div class="modal exploreNowModal" id="exploreNowformmodal" tabindex="-1" role="dialog" aria-labelledby="exploreNowformmodalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl100" role="document">
    <div class="modal-content">
      <div class="modal-body explorenowmdl">
      	<div class="explorenowmdl-wrapper">
      	 <button type="button" class="close explorenowmddl-togglebtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><img src="images/explorenowpopupimgClose.png" alt="close"></span>
        </button>
        	<div class="explorecontwrprr">
        		<h1 class="exploremdlTXt">MyValue365.com is now Quicklly.com</h1>
        		<!--<a href=" javascript:void(0)<?php /*if(isset($_COOKIE['postalcode']) && $_COOKIE['postalcode']!=''){ echo SITE_URL."indian-grocery-delivery/"; }else{ echo SITE_URL; } */ ?>" class="btn explorenwBtn explorenowmddl-togglebtn" data-dismiss="modal">Explore Now!</a>-->
				<!--<span class="btn explorenwBtn explorenowmddl-togglebtn" data-dismiss="modal">Explore Now!</span>
        	</div>
          </div>
      </div>
      </div>
    </div>
  </div>-->
  	<!-- explore now modal ends-->

 <?php 
 include("js.php");
 include("footer.php");
 ?>
<style>
.fotermdlexplore{
    position: relative;
}
#exploreNowformmodal .modal-content{
    background-image: url('images/explorenowpopupimg.png');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
}
/*#exploreNowformmodal .close {
    padding: 1rem 1rem;
    margin: -1rem -1rem -1rem auto;
    background: transparent;
    border: none;
}
#exploreNowformmodal .close span {
    font-size: 18px;
    padding: 2px 5px;
    border: 1px solid green;
    border-radius: 50%;
}*/
button.close.explorenowmddl-togglebtn {
    border: none;
    position: absolute;
    right: 10px;
    background: transparent;
}
.explorecontwrprr {
    text-align: center;    
    padding: 30px 0px 5px;

}
#exploreNowformmodal .close img{
	width:24px;
}
a.explorenwBtn {
    min-width: 260px;
    margin-bottom: 10px;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    /*background-color: var(--primary-color);*/
    background-color: #4fa700;
    border: none;   
    padding: 10px 70px;
    color: #fff;
    border-radius: 50px;
    outline: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 28px;
    transition: 0.2s ease-out;
    box-shadow: 2px 4px 2px #42433d;
}
span.explorenwBtn {
    min-width: 260px;
    margin-bottom: 10px;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    /*background-color: var(--primary-color);*/
    background-color: #4fa700;
    border: none;   
    padding: 10px 70px;
    color: #fff;
    border-radius: 50px;
    outline: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 28px;
    transition: 0.2s ease-out;
    box-shadow: 2px 4px 2px #42433d;
}
h1.exploremdlTXt {
    font-size: 48px;
    color: #42433d;
    font-weight: 900;
    padding: 5px;
}
@media(max-width: 992px){
    .logoItemWrppr {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
	.logoItem_element {
        width: 170px;
        height: 170px;
        padding: 20px;
        background-color: #f7f7f8;
        border: 1px solid #eee;
        border-radius: 50%;
        box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.12);
        margin: 20px 15px;
    }

}
@media(max-width: 600px){
    .logoItemWrppr {
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }
       .logoItem_element {
        /* max-width: 17%; */
        width: 160px;
        height: 160px;
        padding: 15px;
        background-color: #f7f7f8;
        border: 1px solid #eee;
        border-radius: 50%;
        box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.12);
        margin: 15px 10px;
    }
    .button-group.filters-button-group {
        display: flex;
        flex-wrap: wrap;
    }
    .filters-button-group .fltrnav-element{
        flex: 1 0 31%;
        border-bottom: 1px solid #ccc;
    }
    .button-group.filters-button-group{
        padding: 5px 0 0px;
    }
    h1.exploremdlTXt {
	    font-size: 38px;
	}

}
@media(max-width: 320px){
    .logoItemWrppr {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
        }
    .logoItem_element {
        /* max-width: 17%; */
        width: 135px;
        height: 135px;
        padding: 10px;
        background-color: #f7f7f8;
        border: 1px solid #eee;
        border-radius: 50%;
        box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.12);
        margin: 15px 10px;
    }
      .button-group.filters-button-group {
        display: flex;
        flex-wrap: wrap;
    }
    .filters-button-group .fltrnav-element{
        flex: 1 0 31%;
        border-bottom: 1px solid #ccc;
    }
    .button-group.filters-button-group{
        padding: 5px 0 0px;
    }
    h1.exploremdlTXt {
	    font-size: 32px;
	}
	a.explorenwBtn {
		padding: 10px 45px;
	}
	span.explorenwBtn {
		padding: 10px 45px;
	}
}
</style>
<!-- explore now modal starts-->
	<?php /* if($_REQUEST['redirect']!='MyValue365.com'){  ?>	
	<script>
    		(function($){
			if (!sessionStorage.explorenowenable) {
                $('#exploreNowformmodal').show();
                $('body').toggleClass('modal-open');
                 $('body').on('click','.explorenowmddl-togglebtn',function(e){
                        e.preventDefault();
                        $('body').toggleClass('modal-open');
                        $('#exploreNowformmodal').hide();
                    });
			 sessionStorage.explorenowenable = 1;
			}
			})(jQuery);
		</script>
		<?php } */ ?>
		<!-- explore now modal ends-->
		
    <script type="text/javascript">
        window.onscroll = function () { checkScroll() };
        var header = document.getElementsByTagName("header")[0];
        var sticky = header.offsetTop;

        function checkStoreZip(){
            if(getCookie('postalcode').trim() != ''){
                if(getCookie('postalcode').trim() != $('#zipcode').val().trim()){
                    var flgConfirm = confirm('Your cart will get clear on changing zipcode! Continue?');
                    if(flgConfirm)
                        localStorage.clear();
                    else
                        $('#zipcode').val(getCookie('postalcode').trim());
            
                    return flgConfirm;
                }
            }

            return true;
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkScroll() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }

        $('.clsBanner .clsSlider').slick({
            centerMode: true,
            infinite: true,
            variableWidth: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 0,
            arrows: false,
            speed: 30000,
            cssEase: 'linear'
        });

        $('.clsTopStores .clsSlider').slick({
            infinite: true,
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: false,
            arrows: false,
            centerMode: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 1280,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 970,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 690,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });
    </script>
	<!-- Tab Refer Friens JS Start -->
    	<script>
    		(function($){
			$('#referMdl').on('click','.navTabLnk',function(e){
			   e.preventDefault();
				var toshowbTb = $(this).attr('data-panslTb');
				$('.navTabLnk').parent().removeClass('active');
				$('.tab-pane').removeClass('active');
				$(this).parent().addClass('active');
				$('#'+toshowbTb).addClass('active');
			});
			})(jQuery);
    	</script>
    <script>
        /*Refer a Furend Start*/
        $('body').on('click','#referBtn',function(e){
          $('body').toggleClass('modal-open');
          $('#referMdl').toggleClass('show');
        });
        $('body').on('click','.modalReferdismis',function(e){
                            e.preventDefault();
                            $('body').toggleClass('modal-open');
                            $('#referMdl').toggleClass('show');
        });
</script>
        <!--Refer a Furend Ends-->	
</body>
</html>