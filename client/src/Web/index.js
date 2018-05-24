import router from '@system.router'
export default {
  props: [
    'url'
  ],
  data: {
    srcUrl: ""
  },
  onInit () {
    var self = this
    self.srcUrl = self.url
  },
  onBackPress: function(e) {
    var self = this
    var webview = this.$element('web')
    webview.canBack({
      callback: function(e){
        if(e){
          // 加载历史列表中的前一个 URL
          webview.back();
         }else{
          router.back()
         }
      }.bind(self)
    })
    return true;
  },
  onMenuPress: function(e) {
    var self = this
    var prompt = require('@system.prompt');
    var appInfo = require('@system.app').getInfo()
    prompt.showContextMenu({
      itemList: ['重新加载'],
      success: function (ret) {
        switch (ret.index) {
          case 0:
            var webview = self.$element('web')
            webview.reload()
            break;
          default:
        }
      }
    })
  },
  onPageStart: function(e) {
  },
  onPageError: function(e) {
  },
  onPageFinish: function(e) {
  },
  onTitleReceive(e) {
    if (e.title !== "") {
        this.$page.setTitleBar({ text: e.title })
    }
  },
  onError(e) {
  },
  onMenuPress() {
    this.$app.showMenu();
  }
}
 