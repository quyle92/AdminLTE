<template>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card mt-5">
                

                <div class="card-header">
                    <h3 class="card-title">Responsive Hover Table</h3><test></test>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#titleModal" @click.prevent="openAddTitleModal" v-if="permission.write">
                          Add Title
                        </button>
                    </div>
                </div>
       
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Title</th>
                          <th>User ID</th>
                          <th>Created at</th>
                          <th>Modify</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(post, index) in posts" :key='post.id'>
                          <td>{{post.id}}</td>
                          <td>{{post.title | Upper}}</td>
                          <td>{{post.user_id }}</td>
                          <td>{{post.created_at | moment("dddd, MMMM Do YYYY, h:mm:ss a") }}</td>
                          <td>
                              <a href="" v-if="permission.update"  v-on:click.prevent="editPost(post, index)"><span><i class="fas fa-edit blue"></i></span></a>
                              <a href="" v-if="permission.delete"  v-on:click.prevent="deletePost(post, index)"><span><i class="fas de-trash-alt red"></i></span></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                
              <!-- /.card-body -->
            </div>
            </div>
            <pagination :data="laravelData" 
            @pagination-change-page="getResults"
             :limit='2'
             :show-disabled='true'
             >
                  <span slot="prev-nav">&lt; Previous</span>
                  <span slot="next-nav">Next &gt;</span>
            </pagination>

        </div>
        <!-- Modal -->
        <div class="modal fade" id="titleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <form @submit.prevent="addMode ? createPost($event) : updatePost($event)" @keydown="form.onKeydown($event)" enctype="multipart/form-data"><!--(2)-->
                  <AlertError :form="form" />
                  <!-- <AlertErrors :form="form" /> -->
                  <!-- <AlertSuccess :form="form" message="Your changes have beend saved!" /> -->

                  <div class="mb-3">
                    <input id="name" v-model="form.title" type="text" name="name" class="form-control" placeholder="Name">
                    <HasError :form="form" field="name" />
                  </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" >{{addMode ? 'Add' : 'Update'}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                    </div>
                </form>

              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import test from '../components/Test.vue';
    export default {
        data: () => ({
            form: new Form({
              title: '',
            }),
            posts:'',
            selectedPost:'',
            selectedPostIndex:'',
            isDisabled:  false,
            addMode: false,
            addPostButton:true,
            editPostButton:true,
            deletePostButton:true,
            permission: [],
            laravelData:{},
            term:''

        }),
        methods: {
            openAddTitleModal(){
                this.addMode = true;
                $('#titleModal').modal('show');
            },
            async createPost (event) { 
                event.preventDefault();
                const response = await this.form.post('/api/posts');//(3)
                if(response.status == 200)
                {
                    this.form.reset();
                    $('#titleModal').modal('hide');console.log(response.data.post)
                    this.posts.unshift(response.data.post);

                    //for learning only (1)
                    vm.$emit('TitleCreated', this.titles);
                }
            },
            editPost(post, index){
                this.addMode = false;
                $('#titleModal').modal('show');
                this.form.fill(post);//(4)
                this.selectedPost = post;
                this.selectedPostIndex = index;
                this.isDisabled = true;// disable password field when editing Title.
 
            },
            async updatePost(event)
            {   
                event.preventDefault();
                const response = await this.form.put('/api/posts/' + this.selectedPost.id);//(3)
                if(response.status == 200)
                {
                    this.form.reset();
                    $('#titleModal').modal('hide');console.log(this.selectedPostIndex);
                    this.posts[this.selectedPostIndex] = response.data.post;
                }
            },
            deletePost(post, index){
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    const response = this.form.delete('/api/posts/'  + post.id).then( () => {
                            Swal.fire(
                              'Deleted!',
                              'Your file has been deleted.',
                              'success'
                            ).then( () => {
                                setTimeout(() => {
                                    this.posts.splice(index, 1);
                                }, 1000)
                            });

                    }).catch( (e) => {
                        Swal.fire(
                          'Sorry!',
                          'Your file cannot be deleted.',
                          'error'
                        )
                    });
                   
                  }
                })

                
            },
            async getResults(page = 1) {
              if(   this.term.length < 1 ){
                var response = await axios.get('/api/posts?page=' + page);
              }
              else{
                var response = await axios.get('/api/search?page=' + page + '&term=' + this.term);

              }
              this.laravelData = response.data;
              this.posts = response.data.data;
           
              
            },
            // async search(){
            //   const response = await axios.get('/api/search?term=' + this.term);
            //   this.laravelData = response.data;
            //   this.posts = response.data.data;
            // }
            // search: _.debounce( async function (){//(5)
            //     const response = await axios.get('/api/search?term=' + this.term);
            //     this.laravelData = response.data;
            //     this.posts = response.data.data;
            // }, 2000)
        },
        async created()
        {
            this.$Progress.start();
            const response = await axios.get('/api/posts?page=' + 1);
            this.$Progress.finish();
            this.laravelData = response.data;
            this.posts = response.data.data; 
            //for learning only (2)
            vm.$on('search', async (term) =>{
                const response = await axios.get('/api/search?term=' + term);
                this.laravelData = response.data;
                this.posts = response.data.data;
                //assign term to this component data so that getResults() can make use of term. 
                this.term = term;
            });

            let thisResource = this.$route.name;
            
            //get permission for this specific resource (i.e post)
            this.permission = this.$Helper.filterPermission( thisResource );


        },
        mounted () {
            //  [App.vue specific] When App.vue is finish loading finish the progress bar
            this.$Progress.finish()
        },

    }
</script>

<!-- Note 
(1) this one is better than this.$emit as all other components can listen to it (as you already declear window.vm = new Vue(); in app.js)
(2) conditional event binding: 
option 2: v-on="addTitle == true ? { submit: ($event) => createTitle($event) } : { submit: ($event) => updateTitle($event) }". 
Ref: https://stackoverflow.com/questions/48042274/conditional-event-binding-vuejs/57331234#57331234
(3) ko cần pass param vì vform package nó đã làm giùm việc này rồi
(4): IMPORTANT: we use vForm package here so we have to use fill(), which is vForm package api method, to populate the data, else error will throw out.
(5): nếu dùng arrow function ở đây thì this sẽ ko lấy đc hàm bên ngoài.
-->

