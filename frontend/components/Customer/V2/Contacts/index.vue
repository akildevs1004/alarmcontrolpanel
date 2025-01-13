<template>
  <v-expansion-panels accordion>
    <style scoped>
      .customer-table {
        width: 100%;
        border-collapse: collapse;
      }

      .customer-table td {
        padding: 10px;
      }

      .customer-table .label {
        text-align: left;
        color: grey;
        font-size: 11px;
      }

      .customer-table .value {
        text-align: right;
        color: grey;
        font-size: 11px;
      }
    </style>
    <v-expansion-panel
      v-for="(contact, index) in customer_contacts"
      :key="index"
    >
      <v-expansion-panel-header class="text-caption grey--text darken-1">{{
        contact.address_type
      }}</v-expansion-panel-header>
      <v-expansion-panel-content>
        <v-card class="pa-2 my-2" elevation="2">
          <v-container>
            <v-row>
              <!-- Profile Picture -->
              <v-col cols="4">
                <v-card-title class="d-flex flex-column align-items-center">
                  <v-avatar size="80">
                    <img
                      :src="contact.profile_picture || '/no-profile2.png'"
                      @error="onImageError"
                    />
                  </v-avatar>
                  <div class="text-center mt-2">
                    <div class="body-2 mb-0" style="font-size: 9px">
                      {{ contact.title }} {{ contact.full_name }}
                    </div>
                    <p class="text-caption mb-0">
                      <v-icon small color="primary">mdi-whatsapp</v-icon>
                      {{ contact.whatsapp }}
                    </p>
                  </div>
                </v-card-title>
                <v-divider></v-divider>

                <table class="customer-table">
                  <tbody>
                    <tr>
                      <td
                        class="py-1 px-2 label"
                        style="
                          display: flex;
                          border-bottom: 1px solid #e0e0e0;
                          color: grey;
                        "
                      >
                        <span>Email:</span>
                        <span>{{ contact.email }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td
                        class="py-1 px-2 label"
                        style="
                          display: flex;
                          border-bottom: 1px solid #e0e0e0;
                          color: grey;
                        "
                      >
                        <span>Office Phone:</span>
                        <span>{{ contact.office_phone }}</span>
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 px-2 label"
                        style="
                          display: flex;
                          justify-content: space-betweenssss;
                          border-bottom: 1px solid #e0e0e0;
                        "
                      >
                        <span>W.Hrs:</span>
                        <span>{{ contact.working_hours }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </v-col>
              <v-divider vertical></v-divider>
              <!-- Contact Details -->
              <v-col cols="8">
                <div v-if="isEditable" class="text-right">
                  <CustomerV2ContactsEdit
                    :contact="contact"
                    @callrefreshDataFromChild="responseFromChild"
                  />
                </div>
                <table>
                  <tbody>
                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Phone1:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.phone1 }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Phone2:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.phone2 }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Working Hours:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.working_hours }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Latitude:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.latitude }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Longitude:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.longitude }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Distance:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.distance }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Notes:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.notes }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Alarm Stop Pin:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.alarm_stop_pin }}
                      </td>
                    </tr>

                    <tr>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        <span>Address:</span>
                      </td>
                      <td
                        class="py-1 label text-caption"
                        style="border-bottom: 1px solid #e0e0e0; color: grey"
                      >
                        {{ contact.address }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
export default {
  props: [
    "customer",
    "customer_id",
    "customer_contacts",
    "isMapviewOnly",
    "isEditable",
  ],

  data: () => ({
    activeTab: 0, // Bind the active tab
    key: 1,
    dialogEditEmergency: false,
    dialogGoogleMap: false,
    branchesList: [],
    startDateMenuOpen: "",
    endDateMenuOpen: "",
    preloader: false,
    loading: false,
    start_date: "",
    end_date: "",
    editId: "",
    // location: "",
    geographic_payload: {
      location: "",
      lat: "",
      lon: "",
    },
    e1: 1,
    errors: [],
    previewImage: null,
    snackbar: false,
    response: "",
  }),
  created() {},
  computed: {
    columns() {
      if (this.$vuetify.breakpoint.xl) {
        return 4;
      }

      if (this.$vuetify.breakpoint.lg) {
        return 3;
      }

      if (this.$vuetify.breakpoint.md) {
        return 2;
      }

      return 1;
    },
  },
  methods: {
    responseFromChild(response) {
      this.$emit(`callrefreshData`, response);
    },
    onImageError(event) {
      event.target.src = "/no-profile2.png"; // Replace with your default image URL
    },
    can(per) {
      return this.$pagePermission.can(per, this);
    },
    reloadContent() {
      console.log("reloadContent");
      this.dialogEditEmergency = false;
      this.$emit("callrefreshData");
    },
    editContactDetails(editId) {
      this.key = this.key + 1;
      this.editId = editId;
      this.dialogEditEmergency = true;
    },
    closeDialog() {
      //this.key = this.key + 1;
      this.$emit("closeDialog");
      this.dialogEditEmergency = false;
      // location.reload();
      //  this.dialogEditEmergency = false;
    },
  },
};
</script>
