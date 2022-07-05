@extends('layouts.backend')
@section('title','Tambah Data Order')
@section('content')
@if (@$cek_harga->user_id == !null || @$cek_harga->user_id == Auth::user()->id)

@if($message = Session::get('error'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif

<div class="card card-outline-info">
    <div class="card-header">
        <h4 class="card-title">Form Tambah Data Order
            <a href="{{url('customers-create')}}" class="btn btn-danger">+ Customer Baru</a>
        </h4>
    </div>
    <div class="card-body">
        {{-- Cek Apakah Customer ada --}}
        @if ($cek_customer != 0)
        <form action="{{route('pelayanan.store')}}" method="POST">
            @csrf
            <div class="form-body">
                <div class="row p-t-20">
                    <div class="col-md-3">
                        <div class="form-group has-success">
                            <label class="control-label">Nama Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control select2 @error('customer_id') is-invalid @enderror" >
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customer as $customers)
                                <option value="{{$customers->id}}" {{old('customer_id') == $customers->id ? 'selected' : ''}} >{{$customers->name}}</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-success">
                            <label class="control-label">No Transaksi</label>
                            <input type="text" name="invoice" value="{{$newID}}" class="form-control @error('invoice') is-invalid @enderror" readonly>
                            @error('invoice')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group has-success">
                            <label class="control-label">Jenis Pembayaran</label>
                            <select class="form-control custom-select @error('jenis_pembayaran') is-invalid @enderror" name="jenis_pembayaran" >
                                <option value="">-- Pilih Jenis Pembayaran --</option>
                                <option value="Tunai" {{old('jenis_pembayaran' == 'Tunai' ? 'selected' : '')}} >Tunai</option>
                                <option value="Transfer" {{old('jenis_pembayaran' == 'Transfer' ? 'selected' : '')}}>Transfer</option>
                            </select>
                            @error('jenis_pembayaran')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-success">
                            <label class="control-label">Status Pembayaran</label>
                            <select class="form-control custom-select @error('status_payment') is-invalid @enderror" name="status_payment" >
                                <option value="">-- Pilih Status Payment --</option>
                                <option value="Pending" {{old('status_payment') == 'Pending' ? 'selected' : ''}} >Belum Dibayar</option>
                                <option value="Success" {{old('status_payment') == 'Success' ? 'selected' : ''}}>Sudah Dibayar</option>
                            </select>
                            @error('status_payment')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">                  

                    <div class="col-md-2">
                        <div class="form-group has-success">
                            <label class="control-label">Berat Pakaian</label>
                            <input type="text" class="form-control form-control-danger @error('kg') is-invalid @enderror" value=" {{old('kg')}} " name="kg[]" placeholder="Berat Pakaian" autocomplete="off" onchange="hitungTotal();" >
                            @error('kg')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="orm-group has-success">
                            <label class="control-label">Pilih Pakaian</label>
                            <select id="id" name="harga_id[]" class="pilihPakaian form-control select2 @error('harga_id') is-invalid @enderror" onchange="hitungTotal();" onmouseenter ="hitungTotal();">
                                <option value="">-- Jenis Pakaian --</option>
                                @foreach($jenisPakaian as $jenis)
                                <option value="{{$jenis->id}}" {{old('harga_id') == $jenis->id ? 'selected' : '' }} >{{$jenis->jenis}}</option>
                                @endforeach
                            </select>
                            @error('harga_id')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <span class="select-hari-0"></span>
                    </div>
                    <div class="col-md-2">
                        <span class="select-harga-0"></span>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-success">
                            <label class="control-label">Disc</label>
                            <input type="number" name="disc[]" placeholder="Tulis Disc" class="form-control @error('disc') is-invalid @enderror" value="0" onchange="hitungTotal();">
                            @error('disc')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group has-success">
                            <a href="#" class="adddetailtransaksi btn btn-primary" onclick="hitungTotal();"> +</a>
                        </div>
                    </div>
                </div>
                <div class="detailTransaksi"></div>
                <div class="row">                      
                    <div class="col-md-2">
                        <div class="form-group has-success">
                            <label class="control-label">Total Berat Pakaian</label>
                            <input type="text" class="form-control" value="" name="totalKG" id="totalKG" placeholder="Total Berat Pakaian" autocomplete="off" readonly="true">                      
                        </div>
                    </div>   
                    <div class="col-md-3">
                        <div class="form-group has-success">

                        </div>
                    </div>     
                    <div class="col-md-1">
                        <div class="form-group has-success">
                        <div class="form-group has-success">
                            <label class="control-label">Lama Hari</label>
                            <input type="text" class="form-control form-control-danger" value="" name="totalHari" id="totalHari" placeholder="Lama Hari" autocomplete="off" readonly="true">
                        </div>
                        </div>
                    </div>                   
                    <div class="col-md-2">
                        <div class="form-group has-success">
                            <label class="control-label">Total Harga</label>
                            <input type="text" class="form-control form-control-danger" value="" name="totalHarga" id="totalHarga" placeholder="Total Harga" autocomplete="off" readonly="true">
                        </div>
                    </div>   
                    <div class="col-md-2">
                        <div class="form-group has-success">
                            <label class="control-label">Total Disc</label>
                            <input type="text" class="form-control form-control-danger" value="" name="totalDisc" id="totalDisc" placeholder="Total Disc" autocomplete="off" readonly="true">
                        </div>
                    </div>     
                    <div class="col-md-2">
                        <div class="form-group has-success">
                            <label class="control-label">Total</label>
                            <input type="text" class="form-control form-control-danger" value="" name="totalAll" id="totalAll" placeholder="Total" autocomplete="off" readonly="true">
                        </div>
                    </div>                   
                </div>                     
            </div>

            <input type="hidden" name="tgl">
            <!--/row-->
            </div>                 
            <div class="form-actions">
                <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button>
            </div>
        </form>
        @else
        <div class="col text-center">
            <h2 class="text-danger">
                Data Customer Masih Kosong !
            </h2>
        </div>
        @endif
    </div>
</div>
@else
<div class="card">
    <div class="col text-center">
        <img src="{{asset('backend/images/pages/empty.svg')}}" style="height:500px; width:100%; margin-top:10px">
        <h2 class="mt-1">Data Harga Kosong / Tidak Aktif !</h2>
        <h4>Mohon hubungi Administrator :)</h4>
    </div>
</div>
@endif
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript">

    function hitungTotal() {
        var totalKG = 0;
        var totalHari = 0;
        var totalHarga = 0;
        var totalDisc = 0;
        var totalAll = 0;
        var disc = 0;
        var chk_arr = document.getElementsByName("kg[]");        
        var chk_arr_hari = document.getElementsByName("hari[]");        
        var chk_arr_harga = document.getElementsByName("harga[]");        
        var chk_arr_disc = document.getElementsByName("disc[]");   
        var chklength = chk_arr.length;
        for (k = 0; k < chklength; k++)
        {
            if (chk_arr[k].value != '' && chk_arr_hari[k].value != '' &&
                chk_arr_harga[k].value != '' && chk_arr_disc[k].value != '') {
                totalKG = totalKG + parseInt(chk_arr[k].value) ;
                if(totalHari < parseInt(chk_arr_hari[k].value)) {
                    totalHari = parseInt(chk_arr_hari[k].value);
                }  
                totalHarga = totalHarga + (parseInt(chk_arr_harga[k].value) * parseInt(chk_arr[k].value));
                disc = (((parseInt(chk_arr_harga[k].value) * parseInt(chk_arr[k].value))*parseInt(chk_arr_disc[k].value))/100);
                totalDisc = totalDisc + disc;
                totalAll = (totalHarga - totalDisc);
            }
        }
        document.getElementById("totalKG").value = totalKG;
        document.getElementById("totalHari").value = totalHari;
        document.getElementById("totalHarga").value = totalHarga;
        document.getElementById("totalDisc").value = totalDisc;
        document.getElementById("totalAll").value = totalAll;
//        alert('test5 = ' + total);
    }


$('.adddetailtransaksi').on('click', function(){
addDetailTransaksi();
});
function addDetailTransaksi(){
var idQ = $(".removedetailtransaksi").length + 1;
var detailtransaksi = '<div><div class="row"><div class="col-md-2"><div class="form-group has-success"><label class="control-label">Berat Pakaian</label><input type="text" class="form-control form-control-danger @error('kg') is-invalid @enderror" value=" {{old('kg')}} " name="kg[]" placeholder="Berat Pakaian" autocomplete="off" onchange="hitungTotal();" >'
        + '@error('kg')<span class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></span>@enderror</div></div>'
        + '<div class="col-md-3"><div class="orm-group has-success"><label class="control-label">Pilih Pakaian</label><select id="id" name="harga_id[]" class="pilihPakaian form-control select2 @error('harga_id') is-invalid @enderror" onchange="hitungTotal();" onmouseenter ="hitungTotal();"><option value="">-- Jenis Pakaian --</option>'
        + '@foreach($jenisPakaian as $jenis)<option value="{{$jenis->id}}" {{old('harga_id') == $jenis->id ? 'selected' : '' }} >{{$jenis->jenis}}</option>@endforeach'
        + '</select>@error('harga_id')<span class="invalid-feedback text-danger" role="alert"><strong>{{ $message }}</strong></span>@enderror</div></div>'
        + '<div class="col-md-1"><span class="select-hari-' + idQ + '"></span></div>'
        + '<div class="col-md-2"><span class="select-harga-' + idQ + '"></span></div>'
        + '<div class="col-md-2"><div class="form-group has-success"><label class="control-label">Disc</label><input type="number" name="disc[]" placeholder="Tulis Disc" class="form-control @error('disc') is-invalid @enderror" value="0" onchange="hitungTotal();">@error('disc')<span class="invalid-feedback text-danger" role="alert">'
        + '<strong>{{ $message }}</strong></span>@enderror</div></div><div class="col-md-1"><div class="form-group has-success"><a href="#" class="removedetailtransaksi btn btn-danger" onclick="hitungTotal();">-</a></div></div></div></div>';
$('.detailTransaksi').append(detailtransaksi);
addHariHarga(idQ);
};
$('.removedetailtransaksi').live('click', function(){
$(this).parent().parent().parent().remove();
});
function addHariHarga(idQ){
var id = 0;
if (parseInt(idQ) < 0) {
idQ = 0;
}
$.get('{{ Url("listhari") }}', {'_token': $('meta[name=csrf-token]').attr('content'), id:id}, function(resp){
$(".select-hari-" + idQ + "").html(resp);
$.get('{{ Url("listharga") }}', {'_token': $('meta[name=csrf-token]').attr('content'), id:id}, function(resp){
$(".select-harga-" + idQ + "").html(resp);
});
});
};
// Filter Harga
$(document).ready(function() {
var id = $(".pilihPakaian").val();
var idQ = $(this).index('.pilihPakaian');
if (parseInt(idQ) < 0) {
idQ = 0;
}
$.get('{{ Url("listharitransaksi") }}', {'_token': $('meta[name=csrf-token]').attr('content'), id:id}, function(resp){
$(".select-hari-" + idQ + "").html(resp);
$.get('{{ Url("listhargatransaksi") }}', {'_token': $('meta[name=csrf-token]').attr('content'), id:id}, function(resp){
$(".select-harga-" + idQ + "").html(resp);
});
});
});
$(document).on('change', '.pilihPakaian', function (e) {
var id = $(this).val();
var idQ = $(this).index('.pilihPakaian');
$.get('{{ Url("listharitransaksi") }}', {'_token': $('meta[name=csrf-token]').attr('content'), id:id}, function(resp){
$(".select-hari-" + idQ + "").html(resp);
});
});
$(document).on('change', '.pilihPakaian', function (e) {
var id = $(this).val();
var idQ = $(this).index('.pilihPakaian');
$.get('{{ Url("listhargatransaksi") }}', {'_token': $('meta[name=csrf-token]').attr('content'), id:id}, function(resp){
$(".select-harga-" + idQ + "").html(resp);
});
});
</script>
@endsection
