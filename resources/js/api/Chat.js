import Uuid from 'uuid';

export function getChannels() {
    return axios('/api/channels')
        .then(({data}) => (
            data.data.map((channel) => channel)
        ));
}

export function getMessages(channel) {
    return axios(`/api/channels/${channel.id}/messages`)
        .then(({data}) => (
            data.data.map((messages) => messages)
        ));
}

export function sendMessage(channel, message) {
    return axios.post(`/api/channels/${channel.id}/messages`, {
        content: message,
        uuid: Uuid.v4(),
    });
}

export function createChannel(channelName) {
    return axios.post(`/api/channels`, {
        name: channelName,
    });
}

export function logout() {
    return axios.post('/logout')
        .then(() => {
            window.location.replace('/');
        });
}
