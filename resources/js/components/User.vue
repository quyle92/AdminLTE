<template>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="card-title">Responsive Hover Table</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userModal" @click.prevent="openAddUserModal">
                              Add User
                            </button>
                        </div>
                    </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Type</th>
                          <th>Registered in</th>
                          <th>Modify</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(user, index) in users" :key='user.id'>
                          <td>{{user.id}}</td>
                          <td>{{user.name | Upper}}</td>
                          <td>{{user.email}}</td>
                          <td>{{user.type}}</td>
                          <td>{{user.created_at | moment("dddd, MMMM Do YYYY, h:mm:ss a") }}</td>
                          <td>
                              <a href=""  v-on:click.prevent="editUser(user, index)"><span><i class="fas fa-edit blue"></i></span></a>
                              <a href=""  v-on:click.prevent="deleteUser(user, index)"><span><i class="fas fa-trash-alt red"></i></span></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
              <!-- /.card-body -->
            </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" @click="test($event)">button</button>
        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <form @submit.prevent="addMode ? createUser($event) : updateUser($event)" @keydown="form.onKeydown($event)"><!--(2)-->
                  <AlertError :form="form" />
                  <!-- <AlertErrors :form="form" /> -->
                  <!-- <AlertSuccess :form="form" message="Your changes have beend saved!" /> -->

                  <div class="mb-3">{{form.name}}
                    <input id="name" v-model="form.name" type="text" name="name" class="form-control" placeholder="Name">
                    <HasError :form="form" field="name" />
                  </div>

                  <div class="mb-3">
                    <input id="email" v-model="form.email" type="text" name="email" class="form-control"
                    placeholder="email">
                    <HasError :form="form" field="email" />
                  </div>
                  <div class="mb-3">
                    <input id="password" v-model="form.password" type="password" name="password" class="form-control" placeholder="password" v-bind:disabled="isDisabled">
                    <HasError :form="form" field="password" />
                  </div>

                  <div class="mb-3">
                     <label class="mr-sm-2" for="inlineFormCustomSelect">Type</label>
                    <select name="type" class="form-control" v-model="form.type">
                      <option disabled="" value="">Open this select menu</option>
                      <option value="admin">admin</option>
                      <option value="user">user</option>
                      <option value="author">author</option>
                    </select>
                    <HasError :form="form" field="type" />
                  </div>

                  <!-- <div class="mb-3">
                      <label for="exampleFormControlFile1">Example file input</label>
                      <input type="file" class="form-control-file" id="exampleFormControlFile1">
                  </div> -->

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

    export default {
        data: () => ({
            form: new Form({
              name: '',
              email: '',
              password: '',
              type: '',
              //photo: ''
            }),
            users:'',
            selectedUser:'',
            selectedUserIndex:'',
            isDisabled:  false,
            addMode: false
        }),
        methods: {
            test(event){
                console.log(event);

            },
            openAddUserModal(){
                this.addMode = true;
                $('#userModal').modal('show');
            },
            async createUser (event) { 
                event.preventDefault();
                const response = await this.form.post('/api/users');//(3)
                if(response.status == 200)
                {
                    this.form.reset();
                    $('#userModal').modal('hide');
                    this.users.push(response.data.user);

                    //for learning only (1)
                    vm.$emit('userCreated', this.users);
                }
            },
            editUser(user, index){
                this.addMode = false;
                $('#userModal').modal('show');
                this.form.fill(user);
                this.selectedUser = user;
                this.selectedUserIndex = index;
                this.isDisabled = true;// disable password field when editing User.
 
            },
            async updateUser(event)
            {   
                event.preventDefault();
                const response = await this.form.put('/api/users/' + this.selectedUser.id);//(3)
                if(response.status == 200)
                {
                    this.form.reset();
                    $('#userModal').modal('hide');
                    this.users[this.selectedUserIndex] = response.data.user;
                    console.log(this.selectedUserIndex);
                    console.log( this.users[this.selectedUserIndex]);
                    console.log(this.users);
                }
            },
            deleteUser(user, index){
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
                    const response = this.form.delete('/api/users/'  + user.id).then( () => {
                            Swal.fire(
                              'Deleted!',
                              'Your file has been deleted.',
                              'success'
                            ).then( () => {
                                setTimeout(() => {
                                    this.users.splice(index, 1);
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

                
            }
        },
        async created(){
            this.$Progress.start();
            const response = await axios.get('/api/users');

            this.$Progress.finish();
            this.users = response.data.data; 

            //for learning only (2)
            vm.$on('userCreated', (e) =>{
                console.log(e)
            });
                  
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
option 2: v-on="addUser == true ? { submit: ($event) => createUser($event) } : { submit: ($event) => updateUser($event) }". 
Ref: https://stackoverflow.com/questions/48042274/conditional-event-binding-vuejs/57331234#57331234
(3) ko cần pass param vì vform package nó đã làm giùm việc này rồi
-->

