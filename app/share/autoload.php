Cancer diagnosis based on gene expression profile data has attracted extensive attention in the fields of statistics and medicine. 
In practical applications, it suffers from three challenges: noise, gene grouping and adaptive gene selection. 
This paper aims to solve the above problems by developing the logistic regression with adaptive sparse group lasso penalty (LR-ASGL). 
We first propose a noise information processing approach for cancer gene expression profile data via robust principal component analysis. 
Genes are then divided into different groups by performing weighted gene co-expression network analysis on the clean matrix. 
By approximating the relative value of the noise size, gene reliability criterion and robust evaluation criterion are presented. 
Finally, we propose LR-ASGL based on the above ideas, enabling performing cancer diagnosis and adaptive gene selection simultaneously. 
The performance of the proposed method is compared with four methods in three settings: Gaussian noise, uniformly distributed noise and mixed noise. 
The advantages of LR-ASGL in prediction and gene selection are evaluated via ten random data partition experiments on acute leukemia data.