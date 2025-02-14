<template>
  <div>
    <v-tabs right show-arrows class="tabswidthalignment">
      <v-tab
        style="font-size: 10px; min-width: 50px !important"
        v-if="
          contact.address_type.toLowerCase() == 'primary' ||
          contact.address_type.toLowerCase() == 'secondary'
        "
        v-for="(contact, index) in customer.contacts"
        :key="contact.id"
      >
        {{ contact.address_type }}</v-tab
      >
      <v-tab-item
        v-if="
          contact.address_type.toLowerCase() == 'primary' ||
          contact.address_type.toLowerCase() == 'secondary'
        "
        v-for="(contact, index) in customer.contacts"
        :key="contact.id + 50"
        name="index+50"
      >
        <v-card class="elevation-1">
          <v-row>
            <v-col style="margin: auto; padding-top: 0px; max-width: 200px">
              <div style="margin: auto; text-align: center">
                {{ $utils.caps(contact.address_type) }}
              </div>
              <v-img
                style="width: 150px; border: 1px solid #ddd; margin: auto"
                :src="
                  contact?.profile_picture
                    ? contact.profile_picture
                    : '/no-profile-image.jpg'
                "
              ></v-img>
            </v-col>
            <v-col>
              <table
                class="eventcustomertab"
                style="width: 100%; line-height: 30px"
              >
                <tr style="font-weight: bold">
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20">mdi-account-tie</v-icon>
                  </td>
                  <td>
                    {{
                      contact?.first_name
                        ? $utils.caps(contact.first_name) +
                          " " +
                          $utils.caps(contact.last_name)
                        : "---"
                    }}
                  </td>
                </tr>
                <tr>
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20">mdi-map-marker</v-icon>
                  </td>
                  <td>
                    {{
                      contact?.address && contact?.address != "null"
                        ? contact.address
                        : "---"
                    }}
                  </td>
                </tr>
                <tr>
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20">mdi-phone-classic</v-icon>
                  </td>
                  <td>
                    <!-- Office Phone : -->
                    {{ contact?.office_phone ? contact.office_phone : "---" }}
                  </td>
                </tr>
                <tr>
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20"
                      >mdi-cellphone-basic</v-icon
                    >
                  </td>
                  <td>
                    <!-- Mobile : -->
                    {{ contact?.phone1 ? contact.phone1 : "---" }}
                  </td>
                </tr>
                <tr>
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20">mdi-cellphone</v-icon>
                  </td>
                  <td>
                    <!-- Office : -->
                    {{ contact?.phone2 ? contact.phone2 : "---" }}
                  </td>
                </tr>
                <tr>
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20">mdi-whatsapp</v-icon>
                  </td>
                  <td>
                    <!-- Whatsapp : -->
                    {{ contact?.whatsapp ? contact.whatsapp : "---" }}
                  </td>
                </tr>
                <tr>
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20">mdi-at</v-icon>
                  </td>
                  <td>
                    <!-- E-Mail : -->
                    {{ contact?.email ? contact.email : "---" }}
                  </td>
                </tr>
                <tr>
                  <td style="width: 50px">
                    <v-icon color="#2038c0" size="20"
                      >mdi-message-bulleted</v-icon
                    >
                  </td>
                  <td>
                    Notes :
                    {{ contact?.notes ? contact.notes : "---" }}
                  </td>
                </tr>
              </table>
            </v-col>
          </v-row>
        </v-card>
        <v-card class="mt-5"
          ><v-card-text>
            <!-- <v-divider></v-divider> -->
            <v-row class="mt-1">
              <v-col cols="6" class="text-right">
                <label>
                  Enter
                  <span class="bold">{{
                    contact.address_type?.toLowerCase() == "primary"
                      ? "Primary"
                      : "Secondary"
                  }}</span>
                  Contact Secret Code</label
                ></v-col
              >
              <v-col cols="3">
                <v-text-field
                  label="Contact Secret Code"
                  min="0"
                  max="10"
                  class="employee-schedule-search-box font10"
                  dense
                  outlined
                  flat
                  height="20px"
                  v-model="pin_number"
                  hide-details
                  small
                  style="
                    margin-right: -10px;
                    font-size: 11px;

                    padding-top: 0px;
                  "
                >
                </v-text-field>
              </v-col>

              <v-col cols="3">
                <v-col class="text-center"
                  ><v-btn
                    small
                    color="#203864"
                    style="
                      margin: auto;
                      margin-top: -15px;
                      width: 100px;
                      color: #fff;
                      background-color: #007d1f;
                    "
                    >Submit</v-btn
                  ></v-col
                >
              </v-col>
            </v-row></v-card-text
          ></v-card
        >
      </v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
export default {
  components: {},
  props: ["customer"],
  data: () => ({
    responseList: "",
  }),
  computed: {},
  mounted() {},
  created() {},

  methods: {},
};
</script>
