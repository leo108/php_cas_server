/**
 * Created by leo108 on 16/9/20.
 */

let _ = require('lodash');

Vue.component('admin-user-index', {
    data() {
        return {
            query: {
                enabled: '',
                search: ''
            },
            users: [],
            editUser: {
                id: 0,
                name: '',
                real_name: '',
                email: '',
                password: '',
                enabled: true,
                admin: false
            },
            oauthes: null,
            busy: false,
            isEdit: false,
        }
    },
    ready() {
        this.users = Laravel.data.users.data;
        this.query = Laravel.data.query;
    },
    methods: {
        bool2icon(value) {
            let cls = value ? 'fa-check' : 'fa-times';
            return '<i class="fa ' + cls + '"></i>';
        },
        view_oauth(item) {
            this.oauthes = item.oauth.plugins;
            $('#oauth-dialog').modal();
        },
        edit(item) {
            this.isEdit = true;
            this.editUser.id = item.id;
            this.editUser.name = item.name;
            this.editUser.real_name = item.real_name;
            this.editUser.email = item.email;
            this.editUser.password = '';
            this.editUser.enabled = item.enabled;
            this.editUser.admin = item.admin;
            $('#edit-dialog').modal();
        },
        save() {
            if (this.isEdit) {
                this.update();
            } else {
                this.store();
            }
        },
        store() {
            this.busy = true;
            this.$http.post(Laravel.router('user.store'), this.editUser)
                .then(response => {
                    this.busy = false;
                    alert(response.data.msg);
                    location.reload();
                })
        },
        update() {
            this.busy = true;
            this.$http.put(Laravel.router('user.update', {user: this.editUser.id}), this.editUser)
                .then(response => {
                    this.busy = false;
                    alert(response.data.msg);
                    location.reload();
                })
        },
        showAdd() {
            this.isEdit = false;
            this.editUser.id = 0;
            this.editUser.name = '';
            this.editUser.real_name = '';
            this.editUser.email = '';
            this.editUser.password = '';
            this.editUser.enabled = true;
            this.editUser.admin = false;
            $('#edit-dialog').modal();
        },
        isEmpty(value) {
            return _.isEmpty(value);
        }
    }
});