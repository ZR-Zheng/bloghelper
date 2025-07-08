const botui = new BotUI("Akane");

const sendMessage = (content, delay = 1500, type = 'text') => {
    return botui.message.bot({ delay, content, type });
};

const sendButton = (actions, delay = 1500) => {
    return botui.action.button({ delay, action: actions });
};

const resCircle = (numCircle) => {
    const negEva = ["来点二次元！ 😊", "我劝你善良！ 😆", "搞事情是吧？ 😈", "哇酷哇酷！ 😋", "快把我老婆交出来！😍", "别挡着我看老婆 😆"];
    const negResponse = ["介绍我老婆给你认识下~", "可爱吧 Ciallo～(∠・ω< )⌒☆", "不好意思，老婆归我了！", "哎，我老婆真好看。"];
    const randPic = ["https://api.aimer.live/random-image/wallpaper/index.php",
        "https://www.loliapi.com/acg/?id=1",
        "https://www.loliapi.com/acg/?id=2",
        "https://www.loliapi.com/acg/?id=3",
        "https://www.loliapi.com/acg/?id=4",
        "https://www.loliapi.com/acg/"];

    const negText = negEva[Math.floor(Math.random() * negEva.length)];
    const negResponseText = negResponse[Math.floor(Math.random() * negResponse.length)];
    const targetURL = randPic[Math.floor(Math.random() * randPic.length)];

    return sendButton([
        { text: "牛逼呀！ 😃", value: "and" }
        // { text: negText, value: "gg" }
    ]).then(res => {
        if (res.value === "and") {
            return sendMessage("😘😘😘").then(other);
        } else if (numCircle === 0) {
            return sendMessage("好了，不玩啦！").then(other);
        } else {
            return sendMessage(`${negResponseText}<br /><img src="${targetURL}" class="responsive-img" alt="anime">`, 1500, 'html')
                .then(() => resCircle(numCircle - 1));
        }
    });
};

// 正常对话信息
const other = () => {
    return sendMessage("我喜欢折腾新事物和思考人生 ㄟ(▔,▔)ㄏ ")
        .then(() => sendMessage("略懂Linux/HTML/CSS/JavaScript/Python"))
        .then(() => sendMessage("主要从事市场调研方向的工作"))
        .then(() => sendMessage("目前正作为社畜在社会上艰难求生..."))
        .then(() => sendButton([{ text: "为什么叫 Amber 呢？ 🤔", value: "next" }]))
        .then(() => sendMessage("很久以前，我很喜欢日本歌手Aimer，于是便化用作为自己的英文名，这个域名也是在那个时候注册的。"))
        .then(() => sendMessage("后来工作的时候不知怎么的就变成Amber了(～￣▽￣)～ "))
        .then(() => sendButton([{ text: "没有想过更换域名吗？(ง •_•)ง", value: "next" }]))
        .then(() => sendMessage("暂时没有这个计划吧，其一域名买了十年，其二Aimer至今仍是我的最爱。"))
        .then(() => sendButton([{ text: "您未来有什么计划吗？", value: "next" }]))
        .then(() => sendMessage("求思，求索，寻找自己的未来，做一个有担当的人。"))
        // .then(() => sendMessage("没有读过研究生的人生是不完整的，我一定会回去的！"))
        .then(() => sendButton([{ text: "您是不是漏了什么没说呀？", value: "next" }]))
        .then(() => sendMessage("对对，光顾着说自己了 (～￣▽￣)～ "))
        .then(() => sendMessage("祝您身体健康、心想事成、前程似锦喽！"))
        .then(() => sendButton([{ text: "谢谢哈！那么如何支持您的工作呢？", value: "next" }]))
        .then(() => sendMessage("常来看看就是我最大的荣幸！"))
        .then(() => sendMessage('再次感谢！'));
};

sendMessage("Hi，你好呀！👋👋", 200)
    .then(() => sendMessage("欢迎来到我的小站，我是Amber😊", 1000))
    .then(() => sendMessage("是一个每天在镜子前给自己磕头的硬核...咳咳！", 1000))
    .then(() => resCircle(3));
