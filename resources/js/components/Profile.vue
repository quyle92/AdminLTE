<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-black" style="background: url('https://adminlte.io/themes/AdminLTE/dist/img/photo1.png') center center;">
                      <h3 class="widget-user-username">Elizabeth Pierce</h3>
                      <h5 class="widget-user-desc">Web Designer</h5>
                    </div>
                    <div class="widget-user-image">
                      <img class="img-circle" :src="'/uploads/' + form.photo" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                      <div class="row">
                        <div class="col-sm-4 border-right">
                          <div class="description-block">
                            <h5 class="description-header">3,200</h5>
                            <span class="description-text">SALES</span>
                          </div>
                          <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                          <div class="description-block">
                            <h5 class="description-header">13,000</h5>
                            <span class="description-text">FOLLOWERS</span>
                          </div>
                          <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                          <div class="description-block">
                            <h5 class="description-header">35</h5>
                            <span class="description-text">PRODUCTS</span>
                          </div>
                          <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                </div>
            </div>

            <div class="col-md-9">
          <div class="nav-tabs-custom">
            <div class="tab-content">

              <!-- /.tab-pane -->

              <div class="tab-pane active" id="settings">
                <form class="form-horizontal"  @submit.prevent="update" @keydown="form.onKeydown($event)">
                  <AlertError :form="form" />
                  <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="name" placeholder="Name" v-model="form.name" name="name">
                      <HasError :form="form" field="name" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="email" placeholder="Email" v-model="form.email" name="email">
                      <HasError :form="form" field="email" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="type" class="col-sm-2 control-label">Type</label>
                    <div class="col-sm-10">
                        <select name="type" class="form-control" v-model="form.type">
                          <option disabled="" value="" selected>Open this select menu</option>
                          <option value="admin">admin</option>
                          <option value="user">user</option>
                          <option value="author">author</option>
                        </select>
                        <HasError :form="form" field="type" />
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="photo" class="col-sm-2 control-label">Photo</label>

                    <div class="col-sm-10">
                      <img :src="'/uploads/' + form.photo" v-if="image" width=50 height=50   />
                      <img :src="imageOnUpload" v-if="imageOnUpload" width=50 height=50  />
                      <input type="file" class="form-control-file" id="exampleFormControlFile1" @change="onFileChange" name="photo">
                      <HasError :form="form" field="photo" />
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger" >Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            form: Form.make({
              name: '',
              email: '',
              password: '',
              type: '',
              photo: ''
            }),
            imageOnUpload: '',
            image:true
            
        }),
        methods: {
            onFileChange(e) {
              let files = e.target.files || e.dataTransfer.files;
              if (!files.length)
                return;

              this.createImage(files[0]);
              this.form.photo = files[0]; 

            },
            createImage(file) {

              var imageOnUpload = new Image();
              var reader = new FileReader();
              var vm = this;

              reader.onload = (e) => {
                vm.imageOnUpload = e.target.result;//console.log(typeof vm.imageOnUpload )//string
                this.image = false;
               
              };
              reader.readAsDataURL(file);

            },
            async update(){
                let Data = new FormData();
                let response = await this.form.post('/api/profile?_method=PUT');//(2)
                if(response.status == 200)
                {   
                    Swal.fire({
                        type: 'success',
                        title: 'OK!',
                        text: 'Update OK!'
                    });
                   this.form.fill(response.data.user);//(3)
                }
                
            }
        },
        async created() {
            const response = await axios.get('/api/profile');
             this.form.fill(response.data);
            //this.form = response.data;
         
       
        }
    }
</script>
<!-- Note:
(1)this is asyncronous so it will run after reader.readAsDataURL(file) is finised.
Ref: https://developer.mozilla.org/en-US/docs/Web/API/FileReader (The FileReader object lets web applications asynchronously...)
Ex: https://developer.mozilla.org/en-US/docs/Web/API/FileReader/readAsDataURL
(2): "You should send POST and set _method to PUT (same as sending forms) to make your files visible".
Ref: https://stackoverflow.com/questions/50691938/patch-and-put-request-does-not-working-with-form-data
(3): IMPORTANT: we use vForm package here so we have to use fill(), which is vForm package api method, to populate the data, else error will throw out.
    -->