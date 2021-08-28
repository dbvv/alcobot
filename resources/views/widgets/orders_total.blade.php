<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fa fa-shopping-cart"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Заказов всего</span>
                <span class="info-box-number">{{$count}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fa fa-user"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Пользователей всего</span>
                <span class="info-box-number">{{ $usersCount }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div> <!-- /.row -->


<div class="row">
    <div class="col-md-6 col-sm-6 col-12">
        <form method="POST" action="{{ route('admin.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Импорт</h3>
                    <div class="card-tools">
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputFile">Файл для импорта</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text" id="">Форматы: xls, xlsx</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Импортировать</button>
                </div>
                <!-- /.card-body -->
            </div>
        </form>
    </div>
</div>
