  </br>
  <div class="container" style="height: 100%; display: flex; justify-content: center; align-items: center;">
    <div style="top: 50%; transform:translate(0, -50%); position: absolute;">
      <div class="well">
        <p align="center"><img src="images/logo.png" height="75" /></p>
        <h4 align="center" style="margin: 15px 0 -10px 0;"><b>SISTEM INFORMASI</br>STATUS KELULUSAN SISWA</b></h4>
        <hr>
        <form class="form-horizontal" action="index.php?page=login-admin" method="post">
          <fieldset>
            <div class="form-group" style="min-width: 250px;">
              <input type="text" class="form-control" placeholder="NAMA PENGGUNA" id="Yourname" name="username" value="" onblur="if(this.value=='') this.value='Username';" onfocus="if(this.value=='Username') this.value='';" />
            </div>
            <div class="form-group">
              <input name="password" class="form-control" placeholder="SANDI" type="password" id="password" value="" onblur="if(this.value=='') this.value='Password';" onfocus="if(this.value=='Password') this.value='';" />
            </div>
            <div class="form-group" style="margin-bottom: -10px;">
              <p align="center"><input type="submit" name="submit" id="submit" value="LOG IN SYS" class="btn btn-danger"></p>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>