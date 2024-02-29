<?php

    class clsFormCreator{
        public $html_data;     
        public $log;

        public $r;
       
        function __construct(){
			
			$this->Set_Log(clsClassFactory::$all_vars['log']);
            $this->Set_DataBase(clsClassFactory::$all_vars['r']);
		}

        

        function Set_DataBase($r){
			$this->r=$r;
			
		}

        function Set_Log($log){
			$this->log=$log;
			
		}

function Create_Login_Form($Form_Action=""){

            $output="";
            if($Form_Action==""){
                $Form_Action=$_SERVER['REQUEST_URI'];
            }
            $output='
			<form name="form1" method="post" action="'.$Form_Action.'">
                <table width="300" border="2" align="center" cellpadding="2" cellspacing="1" bgcolor="#97C8F9" id="table">
                    <tr>
                    <td bgcolor="#E6E6E6"><strong>Username:</strong></td>
                    <td bgcolor="#FFFFFF"><input type="text" name="username" id="username"></td>
                    </tr>
                    <tr>
                    <td bgcolor="#E6E6E6"><strong>Password:</strong></td>
                    <td bgcolor="#FFFFFF"><input type="password" name="password" id="password"></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="right" bgcolor="#E6E6E6"><a href="/forgot-password/">Forgotten Your Details?</a>        <input type="submit" name="Submit" id="Submit" value="Submit"></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="right" bgcolor="#E6E6E6"><a href="/register/">Register</a></td>
                    </tr>
                </table>
                </form>';
			return $output;
		}
        /*
        function Create_Country_Select($countryID=0){
            
            $output="";
            $output='<SELECT NAME="countryID" id="countryID">';
            
            
            $sql=$this->r->rawQuery("SELECT id,Country_Name FROM countries");
            while($myrow=$this->r->Fetch_Array($sql)){
                if($countryID==$myrow[0]){
                    $output.="<option value='".$myrow[0]."' selected>$myrow[1]</option>";
                }else{
                    $output.="<option value='".$myrow[0]."'>".$myrow[1]."</option>";
                };
            }
            $output.="</SELECT>";
            
            return $output;
            
        };
        */

function Create_Admin_Member_Register_Form($countryID=0,$domainsID=0,$mod_business_categoriesID=0,$Message="",$Form_Action=""){
            
                $output="";
                if($Form_Action==""){
                    $Form_Action=$_SERVER['REQUEST_URI'];
                }
    
                $country_html=$this->Create_Country_Select();//($countryID);
                $business_category_html=$this->Create_Business_Categories_Select();//($domainsID,$mod_business_categoriesID);
                $output='
                <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top"><form action="'.$Form_Action.'"  method="post" name="form2"  >
                      <span class="pageheading">Add New Account </span><span class="RedText">'.$Message.'</span><br>
                      <br>
                    <br>
                    <span class="RedText"><strong>*</strong></span><strong> Mandatory Fields </strong>
                    <table width="100%" border="0" cellpadding="3" cellspacing="1" id="table">
                      <tr>
                        <td class="tabletitle"><strong>Profile Domain</strong></td>
                        <td class="tablewhite"><input name="subdomain" type="text" id="subdomain" size="45"> .bizdirectory.online</td>
                      </tr>
                      <tr>
                        <td width="163" class="tabletitle"><strong> Business Name<span class="RedText">*</span></strong></td>
                        <td width="352" class="tablewhite"><input name="business_name" type="text" id="name" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Contact Name</strong></td>
                        <td class="tablewhite"><input name="contact_name" type="text" id="contact_name" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong> Email<span class="RedText">*</span></strong></td>
                        <td class="tablewhite"><input name="email" type="text" id="email" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Address 1</strong></td>
                        <td class="tablewhite"><input name="address" type="text" id="address" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Address 2</strong></td>
                        <td class="tablewhite"><input name="address2" type="text" id="address" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Address 3</strong></td>
                        <td class="tablewhite"><input name="address3" type="text" id="address" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Suburb</strong></td>
                        <td class="tablewhite"><input name="suburb" type="text" id="suburb" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>City</strong></td>
                        <td class="tablewhite"><input name="city" type="text" id="city" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>State</strong></td>
                        <td class="tablewhite"><input name="state" type="text" id="state" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Postcode</strong></td>
                        <td class="tablewhite"><input name="postcode" type="text" id="postcode" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Country</strong></td>
                        <td class="tablewhite">'.$country_html.'</td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Phone</strong></td>
                        <td class="tablewhite"><input name="phone" type="text" id="phone" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Mobile</strong></td>
                        <td class="tablewhite"><input name="mobile" type="text" id="mobile" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Fax</strong></td>
                        <td class="tablewhite"><input name="fax" type="text" id="fax" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Website</strong></td>
                        <td class="tablewhite"><input name="website" type="text" id="website" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Username:</strong> </td>
                        <td class="tablewhite"><input name="username" type="text" id="UserName" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Password</strong></td>
                        <td class="tablewhite"><input name="password" type="text" id="password" size="45"></td>
                      </tr>
                      
                      <tr>
                        <td class="tabletitle"><strong>TAX Identifier</strong></td>
                        <td class="tablewhite"><input name="abn" type="text" id="abn" size="45"></td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Business Category</strong></td>
                        <td class="tablewhite">'.$business_category_html.'</td>
                      </tr>
                      <tr>
                        <td class="tabletitle"><strong>Directory Description</strong></td>
                        <td class="tablewhite"><textarea name="business_description" cols="45" rows="4" id="business_description"></textarea></td>
                      </tr>
                      
                      <tr>
                        <td colspan="2" align="center" class="tablewhite">
                        <input name="Submit" type="submit"  class="formbuttons" id="Submit" value="Save" onClick="return confirmSubmit()">
                                  </td>
                      </tr>
                      </table>
                    <p><br>
                      
                      </p>
                    </form></td>
                </tr>
              </table>';
                    return $output;
                    
                }  


                


                
                
    }