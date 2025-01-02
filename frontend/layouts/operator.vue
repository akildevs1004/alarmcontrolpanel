<template>
  <v-app>
    <div class="text-center ma-0">
      <v-snackbar :timeout="1000" v-model="snackbar" top="top" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>

    <v-main
      class="main_bg"
      :style="
        items.length == 0
          ? 'padding-left:  0px;'
          : miniVariant && items.length > 0
          ? 'padding-left: 60px;'
          : 'padding-left: 140px;'
      "
    >
      <v-container style="max-width: 100%">
        <nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import company_menus from "../menus/left_menu.json";
import employee_menus from "../menus/employee.json";
import branch_menus from "../menus/branch.json";
import guard_menus from "../menus/guard.json";
import host_menus from "../menus/host.json";

import company_top_menu from "../menus/company_modules_top.json";

import employee_top_menu from "../menus/employee_modules_top.json";

import controlpanel_top_menu from "../menus/security_top_menu.json";

import AlarmPopupAllAlarmEvents from "../components/Alarm/PopupAllAlarmEvents.vue";

export default {
  head() {
    return {
      link: [
        {
          rel: "stylesheet",
          href: "https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap",
        },
      ],
    };
  },
  components: {
    AlarmPopupAllAlarmEvents,
  },
  data() {
    return {
      displayName: "",
      popupKey: 1,
      key: 1,
      snackbar: false,
      response: "",
      alarm_icons: {},

      wait5Minutes: false,
      globalSearchPopupWidth: "500px",
      globalsearch: "",
      globalSearchPopup: false,
      notificationsMenuItems: [],
      notificationAlarmDevices: {},
      notificationAlarmDevicesContent: {},
      selectedBranchName: "All Branches",
      seelctedBranchId: "",
      branch_id: "",
      menuProperties: {
        alarm_dashboard: {
          elevation: 0,
          selected: "",
        },
        alarm_customersmap: {
          elevation: 0,
          selected: "",
        },
        security_tickets: {
          elevation: 0,
          selected: "",
        },
      },

      topMenu_Selected: "dashboard",
      company_menus,
      employee_menus,
      branch_menus,
      guard_menus,
      host_menus,
      controlpanel_top_menu,
      company_top_menu,
      employee_top_menu,
      pendingLeavesCount: 0,
      pendingNotificationsCount: 0,
      snackNotificationText: "",
      snackNotification: false,
      snackNotificationColor: "black",
      socketConnectionStatus: 0,

      right: true,
      rightDrawer: false,
      color: "",
      sideBarcolor: "background",
      year: new Date().getFullYear(),
      dropdown_menus: [{ title: "setting" }, { title: "logout" }],

      clipped: false,
      open_menu: [],
      drawer: true,
      fixed: false,
      order_count: "",
      logo_src: "",
      logo_src2: "",
      items: [],
      modules: {
        module_ids: [],
        module_names: [],
      },
      clipped: true,

      miniVariant: true,
      title: "MyTime2Cloud",
      socket: null,
      logout_btn: {
        icon: "mdi-logout",
        label: "Logout",
      },
      viewing_page_name: "",

      inactivityTimeout: null,
      dialogAlarmPopupNotificationStatus: false,
      key: 1,
      isBackendRequestOpen: false,
    };
  },
  created() {},

  mounted() {},
  watch: {},
  computed: {},
  methods: {},
};
</script>

<style>
.searchicon .v-input__append-inner {
  background: #8d8d8d !important;
  padding: 0px !important;
  margin: 0px !important;
  padding: 0px !important;
  color: #fff !important;
}

.searchicon .v-input__slot {
  padding-right: 0px !important;
}
.search-autocomplete .v-input__slot {
  min-height: 33px !important;
}
.search-autocomplete .v-label {
  line-height: 15px !important;
}
.search-autocomplete .v-input__icon {
  height: 33px !important;
}
.searchicon .v-input__icon {
  background: #8d8d8d !important;
}

.bgwhite .v-input__slot {
  background-color: #fff !important;
}
#markerLayer img {
  animation: pulse 0.5s infinite alternate;
  -webkit-animation: pulse 0.5s infinite alternate;
  transform-origin: center;
  -webkit-transform-origin: center;
}
@media (max-width: 1000px) {
  .google-map-right-hand-content {
    max-width: 100% !important;
    min-width: 100% !important;
  }
}
.global-search-textbox fieldset,
.global-search-textbox .v-label,
.global-search-textbox .v-icon,
.global-search-textbox .theme--dark.v-input textarea {
  color: black !important;
}
.global-search-textbox.v-text-field--enclosed:not(.v-text-field--rounded)
  > .v-input__control
  > .v-input__slot {
  padding: 0 4px;
}
.global-search-textbox.v-input input::placeholder {
  color: black !important;
}

.global-search-textbox .v-input__slot {
  padding: 0 3px !important;
}
.main-leftcontent {
}
.main-rightcontent {
  /* min-width: 300px !important;
  max-width: 300px !important; */

  min-width: 250px !important;
  max-width: 250px !important;
  /* 
  min-width: 450px !important;
  max-width: 450px !important; */
}
.radioheight .v-radio {
  margin-bottom: -3px !important;
}
.events-leftcontent .radiogroup-small .v-input--selection-controls__ripple {
  height: 14px !important;
  width: 14px !important;
  left: -5px !important;
  margin: 10px !important;
  top: calc(50% - 18px) !important;
}
.radiogroup-small .v-icon {
  font-size: 14px !important;
}

.buttongroup-small .v-btn.v-btn--active {
  color: blue !important;
}
.buttongroup-small .v-btn__content {
  opacity: 1 !important;
  text-transform: capitalize !important;
}
.buttongroup-small .v-btn.v-btn {
  opacity: 1 !important;
}
.radiogroup-small .v-input--selection-controls__input {
  margin-right: 0px !important;
}
.radiogroup .v-icon {
  font-size: 15px;
}
.radiogroup {
  color: black;
  font-size: 12px;
  margin-top: 0px !important;
}
.radiogroup label {
  color: black !important;
  font-size: 12px;
}
.v-radio--is-disabled label {
  color: #ddd !important;
}
.radio-group {
  font-size: 12px;
}
.radio-group label {
  font-size: 12px !important;
}
.input-small-fieldset1 fieldset {
  height: 33px;
}
.input-small-fieldset1 fieldset input {
  margin-top: -15px;
}
.input-small-fieldset1 .v-input__append-inner {
  margin-top: -2px;
}

.input-small-fieldset1 .v-label {
  top: 3px !important;
}
.selectfilter .v-label--active {
  padding: 0 12px !important;
}
.selectfilter .v-text-field input {
  padding-top: 0px !important;
}

.selectfilter .v-label {
  font-size: 14px !important;
}
.selectfilter .v-select__selections {
  font-size: 12px !important;
}
.selectfilter .v-input__slot {
  min-height: 33px !important;
  padding: 0 5px !important;
}
.selectfilter .v-label {
  line-height: 15px !important;
}
.selectfilter .v-input__icon {
  height: 20px !important;
}
</style>
<style scoped>
.btn-text-size {
  font-size: 15px !important;
}
.leftMenuWidth {
  width: 140px !important;
}
.main_bg {
  padding-left: 140px;
}
.v-list-item {
  text-align: center;
  width: 100%;
}
</style>
<!-- Extra overriting classes-->
<style scoped>
.violet {
  background-color: #6946dd;
}
.bold {
  color: bold;
}
.text--violet {
  color: #6946dd;
}
.v-list-item--active i {
  color: #6946dd;
}

.theme--dark.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled) {
  color: #9aa9b9;
}
.theme--dark.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled)
  i {
  color: #9aa9b9;
}

header,
header button,
header a,
header i {
  color: black !important;
}
header,
.v-sheet {
  z-index: 99 !important;
}

.theme--dark.v-bottom-navigation .v-btn:not(.v-btn--active) {
  color: black !important;
}
.theme--dark.v-bottom-navigation .v-btn--active {
  background: rgb(105, 70, 221);
  color: #fff !important;
}
</style>

<style>
.violet {
  background-color: #6946dd;
}
.bold {
  font-weight: bold;
}
.text--violet {
  color: #6946dd;
}
.view-profile-table-lineheight {
  line-height: 40px;
  width: 100%;
}
.border-bottom {
  border-bottom: 1px solid #ddd;
}
.view-profile-table-lineheight tr {
  border-bottom: 1px solid #ddd;
}
.view-profile-table-lineheight td {
  padding-right: 5px;
  padding-left: 5px;
}

.whitebackground--text {
  background-color: #ecf0f4;
}

/* New Theme  popup_background*/
.v-application .popup_background {
  background-color: #ecf0f4 !important;
}
.popup_background {
  background-color: #ecf0f4 !important;
  color: black !important;
  font-weight: 400 !important;

  font-size: 1.25rem !important;
}
.popup_title,
.v-dialog > .v-card > .v-card__title {
  color: black !important;
  font-weight: 400 !important;

  font-size: 1.25rem !important;
}
.popup_background i {
  color: black !important;
}
.popup_background button {
  color: black !important;
}

.popup_background .v-tabs-bar {
  background-color: #ecf0f4 !important;
  color: black;
  font-weight: 400;

  font-size: 1.25rem;
}
.popup_background .v-tabs-bar i {
  color: black !important;
}
.popup_background .v-toolbar__title {
  color: black !important;
}
.popup_background .v-icon {
  color: black !important;
}

.v-dialog > .v-card > .popup_background {
  background-color: #6946dd !important;
  padding: 5px 6px 5px !important;
  color: #fff !important;
}
.popup_background_white {
}
.v-dialog > .v-card > .popup_background_noviolet {
  background-color: #ecf0f4 !important  ;
  padding: 5px 6px 5px !important;
  color: black !important;
}
.v-dialog > .v-card > .popup_background_white {
  background-color: #fff !important  ;
  padding: 5px 6px 5px !important;
  color: black !important;
}
.popup_background_white .v-icon {
  color: black !important;
}
.popup_background_noviolet .v-tabs-bar {
  background-color: #ecf0f4 !important;
}
.noviolet {
  background-color: #ecf0f4 !important   ;
}
.v-dialog > .v-card > .popup_background > .mdi-close-circle {
  color: #fff !important;
}

/* .theme--dark.v-toolbar.v-sheet {
  background-color: #cfd8dc !important;
} */
/* .v-card {
  background-color: #cfd8dc;
}
.v-card header {
  background-color: #cfd8dc;
}
.v-card .v-card__title {
  color: black;
}
.v-card i {
  color: black;
}

.v-card .v-toolbar__title {
  color: black;
} */

.input-small-fieldset fieldset {
  height: 30px;
}
.input-small-fieldset input {
  margin-top: -15px;
}
.input-small-fieldset .v-input__append-inner {
  margin-top: -2px;
}
.black--text {
  color: black;
}
.black {
  color: black;
}

.iconsize {
  width: 20px;
}

.secondary-value {
  font-size: 10px;
}

.form-control:focus {
  box-shadow: none !important;

  /* height: 32px !important; */
}

.iconsize30 {
  width: 30px;
  height: auto;
}

.basic-table-design {
  tr {
    width: 100%;
    border-bottom: 1px solid #ddd;
  }
}
/* .v-application .primary--text {
  color: #6946dd !important;
  caret-color: #6946dd !important;
} */

.slidegroup1 .v-slide-group {
  height: 34px !important;
}
</style>

<style>
/*! CSS Used from: https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css */
*,
:after,
:before {
  box-sizing: border-box;
}
button {
  border-radius: 0;
}
button:focus:not(:focus-visible) {
  outline: 0;
}
button {
  margin: 0;
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
}
button {
  text-transform: none;
}
[type="button"],
button {
  -webkit-appearance: button;
}
[type="button"]:not(:disabled),
button:not(:disabled) {
  cursor: pointer;
}
.btn {
  display: inline-block;
  color: #4f4f4f;
  text-align: center;
  text-decoration: none;
  vertical-align: middle;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
  background-color: transparent;
  border: 0.125rem solid transparent;
  padding: 0.375rem 0.75rem;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
@media (prefers-reduced-motion: reduce) {
  .btn {
    transition: none;
  }
}
.btn:hover {
  color: #4f4f4f;
}
.btn:disabled {
  pointer-events: none;
  opacity: 0.65;
}
button:focus {
  outline: 0;
}
.btn {
  text-transform: uppercase;
  vertical-align: bottom;
  border: 0;
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.1);
  font-weight: 500;
  padding: 0.625rem 1.5rem 0.5rem;
  font-size: 0.75rem;
  line-height: 1.5;
}
.btn:active,
.btn:active:focus,
.btn:focus,
.btn:hover {
  box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.2), 0 4px 20px 0 rgba(0, 0, 0, 0.1);
}
.btn:disabled {
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.1);
  border: 0;
}
.btn:focus {
  outline: 0;
  box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.2), 0 4px 20px 0 rgba(0, 0, 0, 0.1);
}
.btn-block {
  display: block;
  width: 100%;
}
.btn-block + .btn-block {
  margin-top: 0.5rem;
}
/*! CSS Used from: https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.4.95/css/materialdesignicons.min.css */
.mdi:before {
  display: inline-block;
  font: normal normal normal 24px/1 "Material Design Icons";
  font-size: inherit;
  text-rendering: auto;
  line-height: inherit;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.mdi-chevron-right::before {
  content: "\F142";
}
/*! CSS Used from: https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css */
.mdi:before {
  display: inline-block;
  font: normal normal normal 24px/1 "Material Design Icons";
  font-size: inherit;
  text-rendering: auto;
  line-height: inherit;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.mdi-chevron-right::before {
  content: "\F0142";
}
/*! CSS Used from: Embedded */
*,
:after,
:before {
  background-repeat: no-repeat;
  box-sizing: inherit;
}
:after,
:before {
  text-decoration: inherit;
  vertical-align: inherit;
}
* {
  margin: 0;
  padding: 0;
}
button {
  font: inherit;
}
button {
  overflow: visible;
}
button {
  text-transform: none;
}
[type="button"],
button {
  color: inherit;
  cursor: pointer;
}
button,
html [type="button"] {
  -webkit-appearance: button;
}
button {
  background-color: transparent;
  border-style: none;
}
.v-icon.v-icon {
  font-feature-settings: "liga";
  align-items: center;
  display: inline-flex;
  font-size: 24px;
  justify-content: center;
  letter-spacing: normal;
  line-height: 1;
  position: relative;
  text-indent: 0;
  transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1), visibility 0s;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
  vertical-align: middle;
}
.v-icon.v-icon:after {
  background-color: currentColor;
  border-radius: 50%;
  content: "";
  display: inline-block;
  height: 100%;
  left: 0;
  opacity: 0;
  pointer-events: none;
  position: absolute;
  top: 0;
  transform: scale(1.3);
  transition: opacity 0.2s cubic-bezier(0.4, 0, 0.6, 1);
  width: 100%;
}

.apexcharts-menu-icon {
  padding-top: 10px;
}
</style>
<style>
.v-application .error--text {
  color: red;
}
.error--text,
.text-danger {
  font-size: 13px;
  color: red !important;
}
.v-messages {
  min-height: 0px !important;
}

.text-break-dot {
  text-overflow: ellipsis;
  width: 200px;
  overflow: hidden;
  white-space: nowrap;
  height: 25px;
}
.breakthewords {
  display: -webkit-box;
  font-size: 12px !important;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.branchlogo {
  width: 50px;
}

.table_active {
  background: #e1e1e1;
  border-left: 1px solid green;
  font-weight: bold;
}
.annnouncment_table .text-left {
  padding: 0px !important;
}
.announ_priority {
  color: rgb(63, 81, 181);
  width: 100%;
  text-align: center;
  padding-bottom: 10px;
  font-weight: bold;
  font-size: 19px;
}
.slidegroup1 .v-slide-group {
  height: 34px !important;
}

.icon-blue {
  color: #755fc9 !important;
}

.company_logo .v-image__image--cover {
  background-size: contain;
}

.no-border > .v-input__control > .v-input__slot:before {
  border-color: #fff !important;
}

/* .no-border:before {
  border-color: #fff !important;
} */

.logtable .v-data-table__wrapper {
  height: 670px;
  overflow-y: scroll;
}
.logtable-comm .v-data-table__wrapper {
  height: 830px;
  overflow-y: scroll;
}

.alarm {
  animation: changeBackgroundColor 1s infinite;
}

@keyframes changeBackgroundColor {
  0% {
    color: #000000;
  }
  10% {
    color: #f73030;
  }
  20% {
    color: #000000;
  }

  40% {
    color: #f73030;
  }
  60% {
    color: #000000;
  }
  80% {
    color: #fc0000;
  }
  90% {
    color: #000000;
  }
  100% {
    color: #fc0000;
  }
}
</style>

<style>
* {
  font-family: "Source Sans Pro", sans-serif !important;
}

.apexcharts-canvas {
  width: 100%;
}
</style>

<style>
.theme--light.v-text-field > .v-input__control > .v-input__slot:before {
  border-color: #fff !important;
}

.no-border:before {
  border-color: #fff !important;
}

.global-search-textbox fieldset,
.global-search-textbox .v-label,
.global-search-textbox .v-icon,
.global-search-textbox .theme--dark.v-input textarea {
  color: black !important;
}
.global-search-textbox.v-text-field--enclosed:not(.v-text-field--rounded)
  > .v-input__control
  > .v-input__slot {
  padding: 0 4px;
}
.global-search-textbox.v-input input::placeholder {
  color: black !important;
}

.global-search-textbox .v-input__slot {
  padding: 0 3px !important;
}
.company-profile-picture .v-image__image--cover {
  background-size: contain;
}
.custom-text-field-height .v-input__slot {
  min-height: auto !important;
  display: flex !important;
  align-items: center !important;
}

.custom-text-field-height .v-label {
  top: 6px !important;
}
.custom-text-field-height .v-input__append-inner {
  margin-top: 3px !important;
}
.custom-text-field-height .v-input__prepend-inner {
  margin-top: 3px !important;
}

.employee-schedule-cropdown .mdi-menu-down {
  padding-top: 7px;
}

.employee-schedule-search-box .v-input__slot {
  min-height: 30px !important;
}
.employee-schedule-search-box .v-label {
  line-height: 11px !important;
}
.employee-schedule-search-box .v-input__icon {
  height: 17px !important;
}
.reports-events-autocomplete .v-input__slot {
  min-height: 33px !important;
}
.reports-events-autocomplete .v-label {
  line-height: 15px !important;
}
.reports-events-autocomplete .v-input__icon {
  height: 20px !important;
}
.table-payslip td {
  text-align: left;
  padding: 0px;

  /* line-height: 30px; */
}
.table-payslip table,
.table-payslip div {
  border-collapse: collapse;
  font-size: 13px;
  color: black;
}

.outer-wrapper {
  display: inline-block;
  margin: 0px;
}
.frame {
  width: 50px;
  height: auto;
  border: 1px solid black;
  vertical-align: middle;
  text-align: center;
  display: table-cell;
}
.img1 {
  max-width: 100%;
  max-height: 100%;
  display: block;
  margin: 0 auto;
}
.header-menu-item {
  font-size: 16px;
  font-weight: 600;
}

.customer-tabs a {
  font-size: 12px !important;
}
/* .customer-tabs i {
  font-size: 12px !important;
} */

#AlamCustomerSensorPieChart .apexcharts-legend {
  top: 0px !important;
}
.notificationbadge {
  z-index: 9999;
}
</style>

<style>
/*  //ipad 1300 to 1600  */
/* @media (max-width: 1600px) {
  .header-menu,
  .header-menu-item {
    font-size: 10px !important;
    font-weight: bold !important;
    padding: 0px;
  }
  .header-menu-box {
    padding: 0px;
  }
  .header-menu-button {
    padding: 0px 3px !important;
  }
} */

@media (min-width: 1000px) and (max-width: 1300px) {
  .header-menu,
  .header-menu-item {
    font-size: 14px !important;
    font-weight: bold !important;
    padding: 0px;
  }
  .header-menu-box {
    padding: 0px !important;
  }
  .header-menu-button {
    padding: 0px 0px !important;
  }
  .logo-image {
    width: 100px !important;
  }
}
@media (min-width: 1301px) and (max-width: 1400px) {
  .header-menu,
  .header-menu-item {
    font-size: 15px !important;
    font-weight: 500 !important;
    padding: 6px !important;
  }
  .header-menu-box {
    padding: 0px !important;
  }
  .header-menu-button {
    padding: 0px 6px !important;
  }
  .logo-image {
    width: 100px !important;
  }
}
@media (min-width: 1401px) and (max-width: 1600px) {
  .header-menu,
  .header-menu-item {
    font-size: 14px !important;
    font-weight: bold !important;
    padding: 6px !important;
  }
  .header-menu-box {
    padding: 0px !important;
  }
  .header-menu-button {
    padding: 0px 8px !important;
  }
  .logo-image {
    width: 100px !important;
  }
}
</style>
<style scoped></style>
<style>
.empty-doughnut1 {
  border: 18px solid rgb(150, 150, 150);
  border-radius: 100px;
  height: 110px;
  width: 110px;
  padding-top: 23px;
  padding-left: 2px;
  font-size: 14px;
  margin-top: 8px;
}

.operatormap table,
.operatormap th,
.operatormap td {
  border-top: 1px solid rgb(255, 255, 255);
  border-bottom: 1px solid rgb(255, 255, 255);
  color: #fff;
  border-collapse: collapse;
  text-align: left;
  padding-left: 10px;
  font-size: 13px;
  height: 32px;
}
.operatorcustomerTop1 td {
  border-top: 0px solid rgb(255, 255, 255);
  border-bottom: 1px solid rgb(255, 255, 255);
  color: #fff;
  border-collapse: collapse;
  text-align: left;
  padding-left: 10px;
  font-size: 13px;
  height: 28px;
}
.eventcustomertab td {
  border-top: 0px solid rgb(255, 255, 255);
  border-bottom: 1px solid rgb(255, 255, 255);
  color: #fff;
  border-collapse: collapse;
  text-align: left;
  padding-left: 10px;
  font-size: 13px;
  height: 20px;
}
.eventcustomertabselect td {
  border-top: 0px solid rgb(255, 255, 255);
  border-bottom: 0px solid rgb(255, 255, 255);
  color: #fff;
  border-collapse: collapse;
  text-align: left;
  padding-left: 10px;
  font-size: 13px;
  height: 35px;
}
.main-leftcontent .v-navigation-drawer__content {
  background-color: #516067;
}
.customerEmergencyContactTabsBGcolor .v-slide-group__content {
  background-color: #909a9f;
}
.customerEmergencyContactTabsBGcolor .v-slide-group__content .v-tab {
  color: #fff !important;
  font-size: 10px;
}
.white-color textarea,
.white-color label,
.white-color .v-input--selection-controls__ripple {
  color: #fff !important;
}

.border-white .v-select__selection,
.border-white .v-select__selection .v-select__selection--comma,
.border-white .v-input__icon {
  color: #fff;
}
.border-white fieldset {
  border-color: #fff !important;
}
</style>
<!-- <link rel="stylesheet" href="../static/css/textbox-label-style.css" /> -->
