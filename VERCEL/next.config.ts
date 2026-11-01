import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  reactStrictMode: true,
  images: {
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'pub-78d31b91bd844251bac9de590ad73bc6.r2.dev',
        pathname: '/**',
      },
      {
        protocol: 'https',
        hostname: '*.r2.dev',
        pathname: '/**',
      },
      {
        protocol: 'https',
        hostname: 'ce.uptpc.edu.ve',
        pathname: '/**',
      },
      {
        protocol: 'https',
        hostname: 'www.uptpc.edu.ve',
        pathname: '/**',
      },
    ],
  },
};

export default nextConfig;
