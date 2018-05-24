import router from '@system.router'
import prompt from '@system.prompt'
import webview from '@system.webview'
import storage from '@system.storage'
import fetch from '@system.fetch'
// import device from '@system.device'

export default {
    data: {
        title: 'H5游戏开服表',
        currentIndex: 0,
        tabs: [{
                "name": "热门",
                "tag": "1"
            },
            {
                "name": "传奇",
                "tag": "2"
            },
            {
                "name": "魔幻",
                "tag": "3"
            },
            {
                "name": "仙侠",
                "tag": "4"
            },
            {
                "name": "其他",
                "tag": "5"
            }
        ]
    },
    onInit() {
    },
    onShow() {
    },
    onHide() {
    },
    onBackPress() {
    },
    onMenuPress() {
        this.$app.showMenu();
    },
    onTabChange: function(e) {
        if (e) {
            this.currentIndex = e.index
            this.isRefreshing = false
        } else {
            this.isRefreshing = false

        }
    },
    gotocenter:function(){
        let time = Date.parse(new Date());
        storage.get({
            key:'time',
            success: function (data) {
                router.clear()
                if(data < time){
                    router.push({
                        uri: '/Login'
                    })
                }else{
                    router.push({
                        uri: '/Center'
                    })
                }
            },
            fail: function (data, code) {
                router.clear()
                router.push({
                    uri: '/Login'
                })
            }
        })
    }
}