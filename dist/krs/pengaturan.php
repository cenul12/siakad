
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Pengaturan KRS</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Pengaturan KRS
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">
                <?php 
                    include 'config/database.php';
                
                    $hasil=mysqli_query($kon,"select * from pengaturan_krs p left join semester s on s.id_semester=p.id_semester limit 1");
                    $data = mysqli_fetch_array($hasil);
                    $status_krs=$data['status_krs'];
                    $id_semester=$data['id_semester'];
                    $semester=$data['semester'];
                    $nama_status="";
                ?>
                <div class="row">
                    <form action="dist/krs/simpan-pengaturan.php" method="post">
                        <div class="col-sm-5">
                        <?php
                            if (isset($_GET['setting'])) {
                                if ($_GET['setting']=='berhasil'){
                                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Pengaturan KRS disimpan</div>";
                                }else if ($_GET['setting']=='gagal'){
                                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Pengaturan KRS gagal disimpan</div>";
                                }    
                            }
                        ?>
                            <div class="form-group">
                                <label>Pilih Status KRS</label>
                                <select class="form-control" name="status_krs" id="status_krs">
                                    <?php
                                        $status = array("1","0");
                                        $jum=count($status)-1;
                                        for ($i=0;$i<=$jum;$i++):

                                            if ($status[$i]==1){
                                                $nama_status="Aktif";
                                            }else {
                                                $nama_status="Tidak Aktif";
                                            }

                                        ?>
                                        <option <?php if ($status[$i]==$status_krs) echo "selected"; ?> value="<?php echo $status[$i];?>"><?php echo $nama_status;?></option>
                                        <?php endfor; ?>
                                </select>        
                            </div>
                            <div class="table-responsive" id="tabel_semester">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Semester</th>
                                            <th>Pilih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        // include database
                                        include 'config/database.php';
                                        $sql="select * from semester";
                                        $hasil=mysqli_query($kon,$sql);
                                        $no=0;
                                        //Menampilkan data dengan perulangan while
                                        while ($data = mysqli_fetch_array($hasil)):
                                        $no++;
                                    ?>
                                    <tr>
                                        <td><?php echo $data['semester']; ?></td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="radio" class="semester" <?php if ($data['id_semester']==$id_semester) echo "checked"; ?> class="form-check-input"  name="id_semester" value="<?php echo $data['id_semester'];?>" required >
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- bagian akhir (penutup) while -->
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                                if ($status_krs=='1') {
                                    echo"<div class='alert alert-info'>Status KRS saat ini aktif. Seluruh mahasiswa dapat melakukan pengisian KRS pada semester ".$semester.".</div>";
                                }else {
                                    echo"<div class='alert alert-warning'>Status KRS saat ini sedang tidak aktif karena bukan masa periode KRS.</div>";
                                }    
                            ?>
                            <div class="form-group">
                                <button type="submit" id="submit" name="simpan_pengaturan" class="btn btn-primary">Simpan Pengaturan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div><!--/.row-->


<script>

    $('#tabel_semester').show();
    var status_krs=$("#status_krs").val();

    if (status_krs!=1){
        $('#tabel_semester').hide();
    }else {
        $('#tabel_semester').show();
    }


    //Event saat select box semester dipilih
    $('#status_krs').bind('change', function () {
        var status_krs=$("#status_krs").val();

        if (status_krs!=1){
            $('#tabel_semester').hide(50);
        }else {
            $('#tabel_semester').show(50);
        }

    });

 
</script>

