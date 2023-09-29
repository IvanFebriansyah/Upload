<?php $this->extend("layouts/mobile/backend"); ?>
<?php $this->section("content"); ?>
<template>
    <h1 class="font-weight-medium mb-2"><?= $title; ?></h1>
    <v-card>
        <v-card-title>
            <v-btn color="primary" dark @click="modalAddOpen" large elevation="1">
                <v-icon>mdi-plus</v-icon> <?= lang('App.add') ?>
            </v-btn>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="<?= lang("App.search") ?>" single-line hide-details clearable>
            </v-text-field>
        </v-card-title>
        <v-layout v-resize="onResize" column>
            <v-data-table :headers="datatable" :items="dataKontak" :items-per-page="10" :loading="loading" :search="search" class="elevation-1" loading-text="<?= lang('App.loadingWait'); ?>" dense>
                <template v-slot:item="{ item }">
                    <tr v-if="isMobile">
                        <td>
                            <v-menu left bottom min-width="200px">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on">
                                        <v-icon>mdi-dots-vertical</v-icon>
                                    </v-btn>
                                </template>

                                <v-list dense>
                                    <v-list-item @click="editItem(item)">
                                        <v-list-item-icon class="me-3">
                                            <v-icon color="primary">mdi-pencil</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Edit</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item @click="deleteItem(item)" :disabled="item.id_kontak == 1">
                                        <v-list-item-icon class="me-3">
                                            <v-icon color="error">mdi-delete</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>Hapus</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </td>
                        <td>
                            {{item.id_kontak}}.
                            {{item.nama}}<br />
                            {{item.tipe}}<br />
                            {{item.alamat}}
                        </td>
                        <td>
                            <v-edit-dialog large persistent :return-value.sync="item.email" @save="" @cancel="" @open="" @close="" save-text="Close">
                                Lihat Email
                                <template v-slot:input>
                                    <v-text-field v-model="item.email" type="text" class="pt-3" outlined dense hide-details single-line></v-text-field>
                                </template>
                            </v-edit-dialog>
                            {{item.perusahaan}}<br />
                            {{item.telepon}}
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-layout>
    </v-card>
    <!-- End Table List -->
</template>

<!-- Modal Add -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalAdd" persistent scrollable max-width="900px">
            <v-card>
                <v-card-title><?= lang('App.add') ?> Kontak
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalAddClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form v-model="valid" ref="form">
                        <v-select v-model="tipe" label="Tipe Kontak" :items="dataTipe" item-text="text" item-value="value" :error-messages="tipeError" :loading="loading2" outlined></v-select>

                        <v-text-field v-model="nama" label="Nama" :error-messages="namaError" outlined></v-text-field>

                        <v-text-field v-model="perusahaan" label="Perusahaan" :error-messages="perusahaanError" outlined></v-text-field>

                        <v-text-field v-model="alamat" label="Alamat" :error-messages="alamatError" outlined></v-text-field>

                        <v-row>
                            <v-col>
                                <v-text-field type="number" label="Telepon" v-model="telepon" :error-messages="teleponError" hint="Format 62" persistent-hint outlined></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field v-model="email" :rules="[rules.email]" label="E-mail" :error-messages="emailError" outlined></v-text-field>
                            </v-col>
                        </v-row>

                        <v-text-field type="number" label="NIK KTP" v-model="nikktp" :error-messages="nikktpError" outlined></v-text-field>

                        <v-text-field label="NPWP" v-model="npwp" :error-messages="npwpError" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="saveKontak" :loading="loading" elevation="1">
                        <v-icon>mdi-content-save</v-icon> <?= lang('App.save') ?>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal Add -->

<!-- Modal Edit -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalEdit" persistent scrollable max-width="900px">
            <v-card>
                <v-card-title><?= lang('App.edit') ?> {{namaEdit}}
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalEditClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form ref="form" v-model="valid">
                        <v-select v-model="tipeEdit" label="Tipe Kontak" :items="dataTipe" item-text="text" item-value="value" :error-messages="tipeError" :loading="loading2" outlined></v-select>

                        <v-text-field v-model="namaEdit" label="Nama" :error-messages="namaError" outlined></v-text-field>

                        <v-text-field v-model="perusahaanEdit" label="Perusahaan" :error-messages="perusahaanError" outlined></v-text-field>

                        <v-text-field v-model="alamatEdit" label="Alamat" :error-messages="alamatError" outlined></v-text-field>

                        <v-row>
                            <v-col>
                                <v-text-field type="number" label="Telepon" v-model="teleponEdit" :error-messages="teleponError" hint="Format 62" persistent-hint outlined></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field v-model="emailEdit" :rules="[rules.email]" label="E-mail" :error-messages="emailError" outlined></v-text-field>
                            </v-col>
                        </v-row>

                        <v-text-field type="number" label="NIK KTP" v-model="nikktpEdit" :error-messages="nikktpError" outlined></v-text-field>

                        <v-text-field label="NPWP" v-model="npwpEdit" :error-messages="npwpError" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="updateKontak" :loading="loading" elevation="1">
                        <v-icon>mdi-content-save</v-icon> <?= lang('App.update') ?>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal Edit -->

<!-- Modal Delete -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalDelete" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title>
                    <v-icon color="error" class="mr-2" x-large>mdi-alert-octagon</v-icon> <?= lang('App.confirmDelete'); ?>
                </v-card-title>
                <v-card-text>
                    <div class="mt-5 py-5">
                        <h2 class="font-weight-regular"><?= lang('App.delConfirm') ?></h2>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text large @click="modalDelete = false" elevation="1"><?= lang("App.no") ?></v-btn>
                    <v-btn color="error" dark large @click="deleteKontak" :loading="loading" elevation="1"><?= lang("App.yes") ?></v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal Delete -->
<?php $this->endSection("content") ?>

<?php $this->section("js") ?>
<script>
    // Mendapatkan Token JWT
    const token = JSON.parse(localStorage.getItem('access_token'));

    // Menambahkan Auth Bearer Token yang didapatkan sebelumnya
    const options = {
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    };

    // Deklarasi errorKeys
    var errorKeys = []

    // Initial Data
    dataVue = {
        ...dataVue,
        isMobile: false,
        modalAdd: false,
        modalEdit: false,
        modalDelete: false,
        modalShow: false,
        search: "",
        datatable: [{
            text: '<?= lang('App.action') ?>',
            value: 'actions',
            sortable: false
        }, {
            text: 'Nama',
            value: 'nama'
        }, {
            text: 'Telepon',
            value: 'telepon'
        }, ],
        dataKontak: [],
        dataTipe: [{
            text: 'Pelanggan',
            value: 'Pelanggan'
        }, {
            text: 'Vendor/Supplier/Kulakan',
            value: 'Vendor'
        }],
        tipe: "",
        tipeError: "",
        grup: "",
        grupError: "",
        nama: "",
        namaError: "",
        perusahaan: "",
        perusahaanError: "",
        alamat: "",
        alamatError: "",
        telepon: "",
        teleponError: "",
        email: "",
        emailError: "",
        nikktp: "",
        nikktpError: "",
        npwp: "",
        npwpError: "",
        idKontakEdit: "",
        tipeEdit: "",
        namaEdit: "",
        perusahaanEdit: "",
        alamatEdit: "",
        teleponEdit: "",
        emailEdit: "",
        nikktpEdit: "",
        npwpEdit: "",
        idKontakDelete: "",
        namaDelete: "",
    }

    // Vue Created
    createdVue = function() {
        this.getKontak();
    }

    // Vue Computed
    computedVue = {
        ...computedVue,
        passwordMatch() {
            return () => this.password === this.verify || "<?= lang('App.samePassword') ?>";
        }
    }

    // Vue Methods
    methodsVue = {
        ...methodsVue,
        onResize() {
            if (window.innerWidth < 769)
                this.isMobile = true;
            else
                this.isMobile = false;
        },

        // Modal Open
        modalAddOpen: function() {
            this.modalAdd = true;
        },
        modalAddClose: function() {
            this.modalAdd = false;
            this.$refs.form.resetValidation();
        },

        // Get User
        getKontak: function() {
            this.loading = true;
            axios.get('<?= base_url(); ?>api/kontak', options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        this.dataKontak = data.data;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataKontak = data.data;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Save Kontak
        saveKontak: function() {
            this.loading = true;
            axios.post('<?= base_url(); ?>api/kontak/save', {
                    tipe: this.tipe,
                    nama: this.nama,
                    perusahaan: this.perusahaan,
                    alamat: this.alamat,
                    telepon: this.telepon,
                    email: this.email,
                    nikktp: this.nikktp,
                    npwp: this.npwp,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getKontak();
                        this.tipe = "";
                        this.nama = "";
                        this.perusahaan = "";
                        this.alamat = "";
                        this.telepon = "";
                        this.email = "";
                        this.nikktp = "";
                        this.npwp = "";
                        this.modalAdd = false;
                        this.$refs.form.resetValidation();
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        errorKeys = Object.keys(data.data);
                        errorKeys.map((el) => {
                            this[`${el}Error`] = data.data[el];
                        });
                        if (errorKeys.length > 0) {
                            setTimeout(() => this.notifType = "", 4000);
                            setTimeout(() => errorKeys.map((el) => {
                                this[`${el}Error`] = "";
                            }), 4000);
                        }
                        this.modalAdd = true;
                        this.$refs.form.validate();
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Get Item Kontak
        editItem: function(item) {
            this.modalEdit = true;
            this.idKontakEdit = item.id_kontak;
            this.tipeEdit = item.tipe;
            this.namaEdit = item.nama;
            this.perusahaanEdit = item.perusahaan;
            this.alamatEdit = item.alamat;
            this.teleponEdit = item.telepon;
            this.emailEdit = item.email;
            this.nikktpEdit = item.nikktp;
            this.npwpEdit = item.npwp
        },
        modalEditClose: function() {
            this.modalEdit = false;
            this.$refs.form.resetValidation();
        },

        //Update Kontak
        updateKontak: function() {
            this.loading = true;
            axios.put(`<?= base_url(); ?>api/kontak/update/${this.idKontakEdit}`, {
                    tipe: this.tipeEdit,
                    nama: this.namaEdit,
                    perusahaan: this.perusahaanEdit,
                    alamat: this.alamatEdit,
                    telepon: this.teleponEdit,
                    email: this.emailEdit,
                    nikktp: this.nikktpEdit,
                    npwp: this.npwpEdit,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getKontak();
                        this.modalEdit = false;
                        this.$refs.form.resetValidation();
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        errorKeys = Object.keys(data.data);
                        errorKeys.map((el) => {
                            this[`${el}Error`] = data.data[el];
                        });
                        if (errorKeys.length > 0) {
                            setTimeout(() => this.notifType = "", 4000);
                            setTimeout(() => errorKeys.map((el) => {
                                this[`${el}Error`] = "";
                            }), 4000);
                        }
                        this.modalEdit = true;
                        this.$refs.form.validate();
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Get Item Delete
        deleteItem: function(item) {
            this.modalDelete = true;
            this.idKontakDelete = item.id_kontak;
            this.namaDelete = item.nama;
        },

        // Delete Kontak
        deleteKontak: function() {
            this.loading = true;
            axios.delete(`<?= base_url(); ?>api/kontak/delete/${this.idKontakDelete}`, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getKontak();
                        this.modalDelete = false;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.modalDelete = true;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

    }
</script>
<?php $this->endSection("js") ?>