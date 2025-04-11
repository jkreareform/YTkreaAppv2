// Browser-compatible YouTube API service
const API_KEY = 'AIzaSyBdx6dvp5oZM2FlX839AHvhXbIxUDsdS48';

export const getChannelDetails = async (channelId) => {
  try {
    // First, get channel info
    const channelResponse = await fetch(
      `https://www.googleapis.com/youtube/v3/channels?part=snippet,statistics&id=${channelId}&key=${API_KEY}`
    );
    
    if (!channelResponse.ok) {
      throw new Error('Failed to fetch channel data');
    }
    
    const channelData = await channelResponse.json();
    
    if (!channelData.items || channelData.items.length === 0) {
      throw new Error('Channel not found');
    }
    
    return channelData.items[0];
  } catch (error) {
    console.error('Error fetching channel details:', error);
    throw error;
  }
}; 