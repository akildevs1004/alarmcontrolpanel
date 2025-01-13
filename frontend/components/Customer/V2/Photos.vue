<template>
  <span>
    <v-dialog v-model="addDialog" width="400px">
      <IconClose @click="closeAddDialog" left="390" />
      <v-card style="overflow: hidden">
        <v-alert dense flat> Add Photo </v-alert>
        <v-card-text>
          <div class="text-center pb-5 px-5">
            <v-row>
              <v-col>
                <!-- Photo Title Field -->

                <!-- Avatar with Image -->
                <v-container>
                  <v-avatar size="150" class="mb-4" style="border-radius: 10%">
                    <v-img
                      :src="primary_previewImage || '/no-business_profile.png'"
                    ></v-img>
                  </v-avatar>
                </v-container>

                <!-- Upload Button -->
                <v-btn
                  block
                  class="mb-4"
                  style="max-width: 300px"
                  color="primary"
                  outlined
                  small
                  @click="onpick_primary_attachment"
                >
                  <v-icon left>mdi-cloud-upload</v-icon>
                  {{ !primary_upload.name ? "Upload Image" : "Change Image" }}
                </v-btn>

                <v-row>
                  <v-col>
                    <v-text-field
                      label="Photo Title"
                      dense
                      outlined
                      v-model="payload_photos.title"
                      class="mb-2 employee-schedule-search-box"
                      hide-details
                    ></v-text-field>
                    <span
                      v-if="primary_errors && primary_errors.title"
                      class="text-danger text-caption"
                    >
                      {{ primary_errors.title[0] }}
                    </span>
                  </v-col>
                  <v-col>
                    <!-- Display Order Field -->
                    <v-text-field
                      label="Display Order"
                      dense
                      outlined
                      type="number"
                      v-model="payload_photos.display_order"
                      class="mb-2 employee-schedule-search-box"
                      hide-details
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-btn
                  block
                  small
                  color="primary"
                  :loading="loading"
                  @click="submit_primary"
                >
                  Submit
                </v-btn>

                <span
                  v-if="primary_errors && primary_errors.display_order"
                  class="text-danger text-caption"
                >
                  {{ primary_errors.display_order[0] }}
                </span>

                <!-- Hidden File Input -->
                <input
                  type="file"
                  @change="primary_attachment"
                  style="display: none"
                  accept="image/*"
                  ref="primary_attachment_input"
                />

                <!-- File Errors -->
                <span
                  v-if="primary_errors && primary_errors.logo"
                  class="text-danger text-caption mt-2"
                >
                  {{ primary_errors.logo[0] }}
                </span>
              </v-col>
            </v-row>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-row>
      <v-col cols="12">
        <div
          v-if="photos && photos.length == 0"
          style="
            overflow: auto;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
          "
        >
          <v-btn x-small color="primary" @click="addDialog = true"
            ><v-icon small>mdi-plus</v-icon>Photo</v-btn
          >
        </div>

        <div v-else class="text-right">
          <v-btn x-small color="primary" @click="addDialog = true"
            ><v-icon small>mdi-plus</v-icon>Photo</v-btn
          >
        </div>
      </v-col>
      <v-col v-for="(n, index) in photos" :key="index" cols="2">
        <v-card class="mx-auto" color="grey lighten-4" max-width="300">
          <v-hover v-slot:default="{ hover }">
            <v-img
              :title="n.title"
              :src="n.picture"
              :aspect-ratio="1"
              class="grey lighten-2 hover-zoom-card"
            >
              <!-- Floating pencil icon -->
              <style scoped>
                .mdi-pencil-btn {
                  position: absolute;
                  right: 0px;
                  z-index: 2;
                }
              </style>
              <v-btn
                v-if="isEditable"
                icon
                small
                class="mdi-pencil-btn"
                @click="editImage(index, n)"
                color="white"
              >
                <v-icon small>mdi-pencil</v-icon>
              </v-btn>

              <div
                class="text-caption white--text"
                style="
                  height: 100%;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  position: relative;
                "
              >
                <!-- The chip that shows on hover -->
                <v-chip
                  v-show="hover"
                  x-small
                  class="primary pa-2"
                  style="
                    position: absolute;
                    bottom: 16px;
                    left: 50%;
                    transform: translateX(-50%);
                    opacity: 0;
                    transition: opacity 0.3s ease-in-out;
                  "
                  :style="hover ? 'opacity: 1;' : ''"
                  @click="openDialog(index)"
                >
                  View Large
                </v-chip>
              </div>
            </v-img>
          </v-hover>
        </v-card>

        <!-- Popup dialog -->
        <v-dialog v-model="dialogs[index]" max-width="700">
          <IconClose @click="closeDialog(index)" left="690" />
          <v-img
            :src="n.picture"
            class="grey lighten-2"
            style="max-height: 400px; object-fit: contain"
          ></v-img>
        </v-dialog>

        <!-- Edit Dialog -->

        <v-dialog v-model="editDialogs[index]" width="400px">
          <IconClose @click="closeEditDialog(index)" left="390" />
          <v-card style="overflow: hidden">
            <v-alert dense flat> Edit Photo </v-alert>
            <v-card-text>
              <div class="text-center pb-5 px-5">
                <v-row>
                  <v-col>
                    <!-- Photo Title Field -->

                    <!-- Avatar with Image -->
                    <v-container>
                      <v-avatar
                        size="150"
                        class="mb-4"
                        style="border-radius: 10%"
                      >
                        <v-img
                          :src="
                            primary_previewImage || '/no-business_profile.png'
                          "
                        ></v-img>
                      </v-avatar>
                    </v-container>

                    <!-- Upload Button -->
                    <v-btn
                      block
                      class="mb-4"
                      style="max-width: 300px"
                      color="primary"
                      outlined
                      small
                      @click="onpick_primary_attachment"
                    >
                      <v-icon left>mdi-cloud-upload</v-icon>
                      {{
                        !primary_upload.name ? "Upload Image" : "Change Image"
                      }}
                    </v-btn>

                    <v-row>
                      <v-col>
                        <v-text-field
                          label="Photo Title"
                          dense
                          outlined
                          v-model="payload_photos.title"
                          class="mb-2 employee-schedule-search-box"
                          hide-details
                        ></v-text-field>
                        <span
                          v-if="primary_errors && primary_errors.title"
                          class="text-danger text-caption"
                        >
                          {{ primary_errors.title[0] }}
                        </span>
                      </v-col>
                      <v-col>
                        <!-- Display Order Field -->
                        <v-text-field
                          label="Display Order"
                          dense
                          outlined
                          type="number"
                          v-model="payload_photos.display_order"
                          class="mb-2 employee-schedule-search-box"
                          hide-details
                        ></v-text-field>
                      </v-col>
                    </v-row>

                    <v-btn
                      block
                      small
                      color="primary"
                      :loading="loading"
                      @click="submit_primary"
                    >
                      Submit
                    </v-btn>

                    <span
                      v-if="primary_errors && primary_errors.display_order"
                      class="text-danger text-caption"
                    >
                      {{ primary_errors.display_order[0] }}
                    </span>

                    <!-- Hidden File Input -->
                    <input
                      type="file"
                      @change="primary_attachment"
                      style="display: none"
                      accept="image/*"
                      ref="primary_attachment_input"
                    />

                    <!-- File Errors -->
                    <span
                      v-if="primary_errors && primary_errors.logo"
                      class="text-danger text-caption mt-2"
                    >
                      {{ primary_errors.logo[0] }}
                    </span>
                  </v-col>
                </v-row>
              </div>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-col>
    </v-row>
  </span>
</template>

<script>
export default {
  props: ["photos", "isEditable"],
  data: () => ({
    dialogs: [], // Array to handle dialog state for each image
    editDialogs: [], // Array to handle dialog state for each image
    primary_errors: [],
    primary_previewImage: null,
    primary_upload: {
      name: "",
    },

    tab: 0,
    editItem: null,
    key: 1,
    building_photos: [],
    addDialog: false,
    dialogEditPhotos: false,
    dialogGoogleMap: false,
    branchesList: [],
    startDateMenuOpen: "",
    endDateMenuOpen: "",
    preloader: false,
    loading: false,
    upload: {
      name: "",
    },
    start_date: "",
    end_date: "",
    editId: "",
    e1: 1,
    errors: [],
    previewImage: null,
    snackbar: false,
    response: "",

    payload_photos: {},
  }),
  created() {
    this.preloader = false;
  },
  mounted() {
    // Initialize dialog state for each image
    this.dialogs = this.photos.map(() => false);
    this.editDialogs = this.photos.map(() => false);
  },
  methods: {
    editImage(index, item) {
      console.log("🚀 ~ editImage ~ item:", item);
      this.payload_photos = {};
      this.payload_photos.editId = item.id;
      this.payload_photos.display_order = item.display_order;
      this.payload_photos.title = item.title;
      this.primary_previewImage = item.picture;
      this.$set(this.editDialogs, index, true);
    },
    closeAddDialog() {
      this.primary_previewImage = null;
      this.payload_photos.display_order = null;
      this.payload_photos.title = null;
      this.addDialog = false;
    },
    closeEditDialog(index) {
      this.$set(this.editDialogs, index, false);
    },
    openDialog(index) {
      this.$set(this.dialogs, index, true);
    },
    closeDialog(index) {
      this.$set(this.dialogs, index, false);
    },
    can(per) {
      return this.$pagePermission.can(per, this);
    },

    onpick_primary_attachment() {
      this.$refs.primary_attachment_input.click();
    },
    primary_attachment(e) {
      this.primary_upload.name = e.target.files[0] || "";

      let input = this.$refs.primary_attachment_input;
      let file = input.files;
      //console.log(file);
      if (file[0] && file[0].size > 1024 * 1024) {
        e.preventDefault();
        this.primary_errors["logo"] = [
          "File too big (> 1MB). Upload less than 1MB",
        ];
        return;
      }
      console.log(file);
      if (file && file[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.primary_previewImage = e.target.result;
        };
        reader.readAsDataURL(file[0]);
        this.$emit("input", file[0]);
      }
    },

    submit_primary() {
      let customer = new FormData();

      for (const key in this.payload_photos) {
        if (this.payload_photos[key] != "")
          customer.append(key, this.payload_photos[key]);
      }

      if (this.primary_upload.name) {
        customer.append("attachment", this.primary_upload.name);
      }

      customer.append("company_id", this.$auth.user.company_id);
      customer.append("customer_id", this.customer_id);
      // if (this.editAddressType != "") customer.append("editAddressType", true);

      if (this.editId != "") {
        customer.append("editId", this.editId);
      }

      this.$axios
        .post("/customers_building_photos", customer)
        .then(({ data }) => {
          //this.loading = false;

          if (!data.status) {
            this.primary_errors = [];
            if (data.primary_errors) this.primary_errors = data.primary_errors;
            this.color = "red";

            this.snackbar = true;
            this.response = data.message;
          } else {
            this.color = "background";
            this.primary_errors = [];
            this.snackbar = true;
            this.response = "Photo Details Updated successfully";
            setTimeout(() => {
              this.$emit("closeDialog");
            }, 1000);
          }
        })
        .catch((e) => {
          if (e.response.data.primary_errors) {
            this.primary_errors = e.response.data.primary_errors;
            this.color = "red";

            this.snackbar = true;
            this.response = e.response.data.message;
          }
        });
    },
  },
};
</script>
