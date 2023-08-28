setTimeout(() => {
    const send = document.createElement("button");
    send.id = "send";
    const url = document.createElement("input");
    url.id = "url";
    
    send.onclick = async () => {
        const result = await fetch(url.value);
        const ok = [200, 304].includes(result.status);
        if(ok) return;
        
        throw new Error("Unexpected HTTP Response!");
    }
    
    document.body.insertAdjacentElement("afterbegin", send);
    document.body.insertAdjacentElement("afterbegin", url);
});