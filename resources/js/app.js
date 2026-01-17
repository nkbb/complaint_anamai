

import { createApp } from 'vue'
import axios from 'axios'
window.axios = axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/css/index.css'
import VueApexCharts from "vue3-apexcharts";



import ComplaintComponent from './components/complaint/Index.vue'
import ImageCarouselComponent from './components/ImageCarousel.vue'
import ImageCarouselBannerComponent from './components/ImageCarouselBanner.vue'


import SettingUnitComponent from './components/setting/Unit.vue'
import SettingTypeComponent from './components/setting/Type.vue'
import SettingTimefinishComponent from './components/setting/Timefinish.vue'
import SettingComplaintComponent from './components/setting/Complaint.vue'
import SettingMethodsComponent from './components/setting/Methods.vue'
import SettingPersonComponent from './components/setting/Person.vue'
import SettingSeverityComponent from './components/setting/Severity.vue'
import SettingQuestionComponent from './components/setting/Question.vue'
import SettingCommentComponent from './components/setting/Comment.vue'
import SettingUsersComponent from './components/setting/Users.vue'
import SettingBannerComponent from './components/setting/Banner.vue'
import SettingPopupComponent from './components/setting/Popup.vue'
import SettingTelegramComponent from './components/setting/Telegram.vue'
import SettingChangePasswordComponent from './components/setting/ChangePassword.vue'

import homeTypeComponent from './components/HomeType.vue'
import HomeFollowComponent from './components/HomeFollow.vue'
import HomeManualComponent from './components/HomeManual.vue'
import HomeAgreementComponent from './components/HomeAgreement.vue'
import HomeEvaluationComponent from './components/HomeEvaluation.vue'
import HomeCommentsComponent from './components/HomeComments.vue'
import HomePopupComponent from './components/HomePopup.vue'
import HomeCookieComponent from './components/HomeCookie.vue'

import ReportModelsComponent from './components/report/Models.vue'
import ReportTypeComponent from './components/report/Type.vue'
import ReportPersonComponent from './components/report/Person.vue'
import ReportOfficeComponent from './components/report/Office.vue'
import ReportHistoryComponent from './components/report/History.vue'
import ReportQuestionnaireComponent from './components/report/Questionnaire.vue'
import ReportSummaryComponent from './components/report/Summary.vue'
import ReportSeverityComponent from './components/report/Severity.vue'

import ListComplaintComponent from './components/admin/ListComplaint.vue'
import InputComplaintComponent from './components/admin/InputComplaint.vue'


const app = createApp({})
app.component('Loading', Loading)
app.component("apexchart", VueApexCharts)
app.component('complaint-component', ComplaintComponent)
app.component('home-type-component', homeTypeComponent)
app.component('image-carouse-component', ImageCarouselComponent)
app.component('home-follow-component', HomeFollowComponent)
app.component('home-manual-component', HomeManualComponent)
app.component('home-agreement-component', HomeAgreementComponent)
app.component('home-evaluation-component', HomeEvaluationComponent)
app.component('home-comments-component', HomeCommentsComponent)
app.component('home-popup-component', HomePopupComponent)
app.component('home-cookie-component', HomeCookieComponent)
app.component('image-carouse-banner', ImageCarouselBannerComponent)

app.component('setting-unit', SettingUnitComponent)
app.component('setting-type', SettingTypeComponent)
app.component('setting-timefinish', SettingTimefinishComponent)
app.component('setting-complaint', SettingComplaintComponent)
app.component('setting-methods', SettingMethodsComponent)
app.component('setting-person', SettingPersonComponent)
app.component('setting-severity', SettingSeverityComponent)
app.component('setting-question', SettingQuestionComponent)
app.component('setting-comment', SettingCommentComponent)
app.component('setting-users', SettingUsersComponent)
app.component('setting-banner', SettingBannerComponent)
app.component('setting-telegram', SettingTelegramComponent)
app.component('setting-popup', SettingPopupComponent)
app.component('setting-change-password', SettingChangePasswordComponent)

app.component('report-models', ReportModelsComponent)
app.component('report-office', ReportOfficeComponent)
app.component('report-history', ReportHistoryComponent)
app.component('report-questionnaire', ReportQuestionnaireComponent)
app.component('report-type', ReportTypeComponent)
app.component('report-person', ReportPersonComponent)
app.component('report-summary', ReportSummaryComponent)
app.component('report-severity', ReportSeverityComponent)

app.component('admin-list-complaint', ListComplaintComponent)
app.component('admin-input-complaint', InputComplaintComponent)




app.mount('#app'); 