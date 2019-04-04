<?php
include_once "../src/include_files/validation.php";

use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase //PHPUnit_Framework_TestCase
{
  public function testTest_Input()
  {
    //test_input($data)
    $this->assertFalse(test_input(" //I_Am! a <html> // P@ssword "));
    $this->assertTrue(test_input("P@ssw0rd"));
    $this->assertFalse(test_input(" Password"));
    $this->assertFalse(test_input("Pass<word"));
  }

  public function testValidateGender()
  {
    //echo validateGender("male");
    $this->assertTrue(validateGender("m"));
    $this->assertTrue(validateGender("f"));
    $this->assertTrue(validateGender("o"));
    $this->assertFalse(is_bool(validateGender("")));
    $this->assertFalse(is_bool(validateGender("dinosaur")));
    $this->assertFalse(is_bool(validateGender(0)));
    $this->assertFalse(is_bool(validateGender(false)));
  }

  public function testValidateName()
  {
    $this->assertTrue(validateName("Kyle"));
    $this->assertTrue(validateName("bEn"));
    $this->assertTrue(validateName("Lyle Byle"));
    $this->assertFalse(is_bool(validateName("")));
    $this->assertFalse(is_bool(validateName("Lyle The 1st.")));
    $this->assertFalse(is_bool(validateName("Lyle%")));
    $this->assertFalse(is_bool(validateName(0)));
    $this->assertFalse(is_bool(validateName(false)));
  }

  public function testValidateEmail()
  {
    $this->assertTrue(validateEmail("lyle.byle@gmail.com"));
    $this->assertTrue(validateEmail("hey@hotmail.org"));
    $this->assertTrue(validateEmail("d@b.c"));
    $this->assertFalse(is_bool(validateEmail("")));
    $this->assertFalse(is_bool(validateEmail("url/scr/hello")));
    $this->assertFalse(is_bool(validateEmail("domain @email.com")));
    $this->assertFalse(is_bool(validateEmail(0)));
    $this->assertFalse(is_bool(validateEmail(false)));
  }

  public function testValidatePassword()
  {
    $this->assertTrue(validatePassword("Passw0rd"));
    $this->assertTrue(validatePassword("Mygreatpassw0rd"));
    $this->assertTrue(validatePassword("Test1"));
    $this->assertTrue(validatePassword("Admin1"));
    $this->assertTrue(validatePassword("P@ssw0rd"));
    $this->assertFalse(is_bool(validatePassword("")));
    $this->assertFalse(is_bool(validatePassword("password")));
    $this->assertFalse(is_bool(validatePassword("p1")));
    $this->assertFalse(is_bool(validatePassword("password 1")));
    $this->assertFalse(is_bool(validatePassword("password1")));
    $this->assertFalse(is_bool(validatePassword("password<html>")));
    $this->assertFalse(is_bool(validatePassword(" P1assword")));
    $this->assertFalse(is_bool(validatePassword(0)));
    $this->assertFalse(is_bool(validatePassword(false)));
  }

  public function testValidateCountry()
  {
    $this->assertTrue(validateCountry("canada"));
    $this->assertTrue(validateCountry("Canada"));
    $this->assertTrue(validateCountry("united states"));
    $this->assertTrue(validateCountry("I am a country"));
    $this->assertTrue(validateCountry("The Mountains"));
    $this->assertFalse(is_bool(validateCountry("")));
    $this->assertFalse(is_bool(validateCountry(0)));
    $this->assertFalse(is_bool(validateCountry(false)));
  }

  public function testValidateProvState()
  {
    $this->assertTrue(validateProvState("BC"));
    $this->assertTrue(validateProvState("bc"));
    $this->assertTrue(validateProvState("NY"));
    $this->assertTrue(validateProvState("ab"));
    $this->assertFalse(is_bool(validateProvState("British Columbia")));
    $this->assertFalse(is_bool(validateProvState("B C")));
    $this->assertFalse(is_bool(validateProvState("<BC>")));
    $this->assertFalse(is_bool(validateProvState("B1")));
    $this->assertFalse(is_bool(validateProvState("")));
    $this->assertFalse(is_bool(validateProvState(0)));
    $this->assertFalse(is_bool(validateProvState(false)));
  }

  public function testValidateCity()
  {
    $this->assertTrue(validateCity("Fort St John"));
    $this->assertTrue(validateCity("Fort St. John"));
    $this->assertTrue(validateCity("kelowna"));
    $this->assertTrue(validateCity("kamlOops"));
    $this->assertTrue(validateCity("Kelowna"));
    $this->assertFalse(is_bool(validateCity("Jasper the 2nd")));
    $this->assertFalse(is_bool(validateCity("$2.00")));
    $this->assertFalse(is_bool(validateCity("")));
    $this->assertFalse(is_bool(validateCity("\"DROP *\"")));
    $this->assertFalse(is_bool(validateCity(0)));
    $this->assertFalse(is_bool(validateCity(false)));
  }

  public function testValidateStreet()
  {
    $this->assertTrue(validateStreet("Homer 1st"));
    $this->assertTrue(validateStreet("3012 University Ave"));
    $this->assertTrue(validateStreet("3012 University aVe."));
    $this->assertTrue(validateStreet("The Biggest Mountain"));
    $this->assertFalse(is_bool(validateStreet("The Biggest Mountain!")));
    $this->assertFalse(is_bool(validateStreet("The Biggest Mountain You Can Find!")));
  }

  public function testPostCode()
  {
    $this->assertTrue(validatePostCode("v1v2h2"));
    $this->assertTrue(validatePostCode("X0X0X0"));
    $this->assertFalse(is_bool(validatePostCode("xx00x0x")));

  }
}
 ?>
