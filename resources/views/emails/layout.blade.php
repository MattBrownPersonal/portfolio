<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
</head>
<head>
<style>
 
@font-face {
  font-family: "Soleil";
  src: url("{{URL::to('/')}}/fonts/soleil_book.woff");
}

.wrapper {
  background-color: #edf2f7;
  color:#3d4852;
  text-align: center;
}

.body{
  font-family: "Soleil", Arial;
  color: #333538;
  background-color: #FFFFFF;
  text-align:left;
  border-radius: 20px;
  padding: 20px 20px 20px 20px;
}

.header
{
  text-align: center;
  font-size:19px;
  font-weight: bold;
  padding-top: 20px;
  padding-bottom: 20px;
}

.header a,
.header a:link,
.header a:active,
.header a:visited,
.header a:hover
{
  color: #3d4852;
  text-decoration: none;
}

.footer{
  color: #b0adc5;
  font-size: 12px;
  margin-top: 20px;
  margin-bottom: 20px;
}

@media only screen and (max-width: 600px) {
  .inner-body {
  width: 100% !important;
  }

  .footer {
  width: 100% !important;
  }
}

@media only screen and (max-width: 500px) {
  .button {
  width: 100% !important;
  }
}
  .mem
  {
    text-align:center;
    margin-top: 20px;
    margin-bottom: 20px;
    padding-top:20px;
    padding-bottom:20px;
  }
  
  .mem-url{
    text-decoration:none;
    padding-left:40px;
    padding-right:40px;
    text-align:center;
    padding-top:20px;
    padding-bottom:20px;
    border-radius: 1000px;
    background-color: #004C3B;
    display: inline-block;
    color:white;
    font-weight:bold;
    font-size: 20px;
  }

  .productDetails {
    border-collapse: collapse
  }
  .productDetails th, .productDetails td {
    padding: 10px;
    border: 1px solid #333538;
    text-align: center;
    margin: 0 0 0 0;
  }
</style>
</head>
<body class="body">

  <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td align="center">
        <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
          <tr>
            <td class="header">
              <a href="{{ config('app.name') }}">
                {{ config('app.name') }}
              </a>
            </td>
          </tr>
          <!-- Email Body -->
          <tr>
            <td class="" width="100%" cellpadding="0" cellspacing="0">
              <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <!-- Body content -->
                <tr>
                <td class="content-cell">
                  @yield('content')
                </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                  <td class="content-cell" align="center">
                    © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
